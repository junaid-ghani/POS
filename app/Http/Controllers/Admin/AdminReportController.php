<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use DataTables;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Location;
use App\Models\Product;
use App\Models\User;
use App\Models\Adjustment;
use App\Models\AdjustmentSaleperson;
use App\Models\Bonus;
use Illuminate\Support\Facades\DB;


class AdminReportController extends Controller
{
    public function index()
    {
        $data['startOfMonth'] = Carbon::now()->startOfMonth();
        $data['today'] = Carbon::now();
        $data['set'] = 'Salaries';        
        return view('admin.report.report',$data);
    }

    public function get_salaries(Request $request)
    {        

        $data = DB::table('users as user')
        ->join('commission', 'commission.seller_id', '=', 'user.user_id')
        ->select('user.*', 'commission.*', DB::raw('SUM(commission.amount) as total_sales'))
        ->where('user.user_role', '!=', '1')
        ->where('user.user_status', '=', '1')
        ->whereBetween('commission.commission_added_on', [
            date('Y-m-d 00:00:00', strtotime($request->start_date)),
            date('Y-m-d 23:59:59', strtotime($request->end_date))
        ])
        ->groupBy('user.user_id')
        ->orderBy('user.user_id', 'desc')
        ->get();

        // dd($data);

        return Datatables::of($data)
                ->addIndexColumn()              
                ->addColumn('total_sales',function($row){

                    // dd($row->total_sales);
                    return '$'.number_format((float)$row->total_sales, 2, '.', '');

                })
                ->addColumn('total_commission',function($row){
                
                    if ($row->user_salary_type == 1) 
                    {                        
                        return "$".number_format((float)'0', 2, '.', ''); ;
                    }
                    if ($row->user_salary_type == 2) {
                        
                       $total_commission = $row->total_sales*$row->user_commission/100;
                       return '$'.number_format((float)$total_commission, 2, '.', '');
                    }     
                                   
                    if ($row->user_salary_type == 3) {
                        
                        $userCommission = json_decode($row->user_commission, true);
                    
                        $from = $userCommission['FROM'];
                        $to = $userCommission['TO'];
                        $amount = $userCommission['AMOUNT'];
                    
                        $totalCommission = 0;

                        $salesRecords = DB::table('commission')->where('seller_id', $row->user_id)->get();
                        
                        $dailySales = $row->total_sales;
                            
                        $calculatedCommission = 0;
            
                            foreach ($from as $step => $minValue) {
                                $maxValue = $to[$step];
                                $commissionRate = $amount[$step];
            
                                if ($dailySales >= $minValue && $dailySales <= $maxValue) {
                                    // $calculatedCommission = number_format(floor($dailySales * $commissionRate) / 100,2);
                                    $calculatedCommission = floor($dailySales * $commissionRate) / 100;
                                    break; 
                                }
                            }
                            
                        $totalCommission += $calculatedCommission; 

                        return "$".number_format((float)$totalCommission, 2, '.', '');
                    
                    }
                    
                })
                ->addColumn('bonuses',function($row) use($request) {
                
                    // dd($row);
                    $seller_id = $row->user_id;
                    $bonus = Bonus::where('seller_id',$seller_id)
                    ->whereBetween('bonus.bonus_added_on', [
                        date('Y-m-d 00:00:00', strtotime($request->start_date)),
                        date('Y-m-d 23:59:59', strtotime($request->end_date))
                    ])->get();
                    $bonuses = 0;
                    if ($bonus->isNotEmpty()) {
                    
                        foreach ($bonus as $key => $value) 
                        {
                            $bonuses += $value->bonus;
                        }
                        return '$'.number_format((float)$bonuses, 2, '.', ''); 
                        
                    }
                    else{
                        return number_format((float)'$0', 2, '.', '');
                    }
                 
                })
                ->addColumn('reimbursements',function($row) use($request){

                    $sellerId = $row->user_id;

                    $adjustments = Adjustment::whereHas('adjustment_saleperson', function ($query) use ($sellerId) {
                        $query->where('seller_id', $sellerId);
                    })
                    ->whereBetween('adjustments.adjustment_added_on', [
                        date('Y-m-d 00:00:00', strtotime($request->start_date)),
                        date('Y-m-d 23:59:59', strtotime($request->end_date))
                    ])->get();
               
                    if ($adjustments) {
                    
                        $reimbursement_deduction_amount = 0;
                        foreach ($adjustments as $key => $value) 
                        {
                            if ($value->type == 'reimbursements') {
                                $reimbursement_deduction_amount += $value->reimbursement_deduction_amount;
                            }
                        }
                        return '$'.number_format((float)$reimbursement_deduction_amount, 2, '.', '');                                            
                    }
                    else{
                        return number_format((float)'$0', 2, '.', '');
                    }
                })
                ->addColumn('deductions',function($row) use($request){

                    $sellerId = $row->user_id;

                    $adjustments = Adjustment::whereHas('adjustment_saleperson', function ($query) use ($sellerId) {
                        $query->where('seller_id', $sellerId);
                    })->whereBetween('adjustments.adjustment_added_on', [
                        date('Y-m-d 00:00:00', strtotime($request->start_date)),
                        date('Y-m-d 23:59:59', strtotime($request->end_date))
                    ])->get();
               
                    if ($adjustments) {
                    
                        $reimbursement_deduction_amount = 0;
                        foreach ($adjustments as $key => $value) 
                        {
                            if ($value->type == 'deductions') {
                                
                                $reimbursement_deduction_amount += $value->reimbursement_deduction_amount;
                            }
                        }
                        return '$'.number_format((float)$reimbursement_deduction_amount, 2, '.', '');              
                    }
                    else{
                        return number_format((float)'$0', 2, '.', '');
                    }
                })
                ->addColumn('total_Salary',function($row) use($request){
                                       
                    $sellerId = $row->user_id;
                    $adjustments = Adjustment::whereHas('adjustment_saleperson', function ($query) use ($sellerId) {
                        $query->where('seller_id', $sellerId);
                    })
                    ->whereBetween('adjustments.adjustment_added_on', [
                        date('Y-m-d 00:00:00', strtotime($request->start_date)),
                        date('Y-m-d 23:59:59', strtotime($request->end_date))
                    ])->get();

                    $seller_id = $row->user_id;
                    $bonus = Bonus::where('seller_id',$seller_id)
                    ->whereBetween('bonus.bonus_added_on', [
                        date('Y-m-d 00:00:00', strtotime($request->start_date)),
                        date('Y-m-d 23:59:59', strtotime($request->end_date))
                    ])->get();
                    $bonuses = 0;
                    if ($bonus->isNotEmpty()) {
                    
                        foreach ($bonus as $key => $value) 
                        {
                            $bonuses += $value->bonus;
                        }                      
                    }
                    $bonuses = $bonuses;
                 
                    $reimbursements = 0;
                    $deductions = 0;
                    foreach ($adjustments as $key => $value) 
                    {
                        if ($value->type == 'reimbursements') {
                            $reimbursements += $value->reimbursement_deduction_amount;                        
                        }
                        if ($value->type == 'deductions') {
                            
                            $deductions += $value->reimbursement_deduction_amount;
                        }
                    }
                   
                    
                    $reimbursements = $reimbursements;
                    $deductions = $deductions;
                
                    // Calculate total commission based on salary type
                    $total_commission = 0;
                    if ($row->user_salary_type == 1) {
                        $total_commission = 0;
                    } elseif ($row->user_salary_type == 2) {
                        $total_commission = $row->total_sales * $row->user_commission / 100;
                    } elseif ($row->user_salary_type == 3) {
                        $userCommission = json_decode($row->user_commission, true);
                        $from = $userCommission['FROM'];
                        $to = $userCommission['TO'];
                        $amount = $userCommission['AMOUNT'];
                
                        $dailySales = $row->total_sales;
                        $calculatedCommission = 0;
                
                        foreach ($from as $step => $minValue) {
                            $maxValue = $to[$step];
                            $commissionRate = $amount[$step];
                
                            if ($dailySales >= $minValue && $dailySales <= $maxValue) {
                                // $calculatedCommission = number_format(floor($dailySales * $commissionRate) / 100, 2);
                                $calculatedCommission = floor($dailySales * $commissionRate) / 100;
                                break;
                            }
                        }
                
                        $total_commission = $calculatedCommission;
                    }
                
                    $total_salary = $total_commission + $bonuses + $reimbursements + (-$deductions);
                
                    return '$' . number_format($total_salary, 2);
                })
                ->addColumn('action', function($row){

                    $btn = '<div class="hstack gap-2 fs-15">';                    
                    $btn .= '<button type="button" class="btn btn-sm btn-dark seller_salary" data-seller_id="'.$row->user_id.'" data-bs-toggle="modal" data-bs-target="#Salary_Report" ><i class="fa-solid fa-box me-2"></i>Open</button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-primary" ><i class="fa-solid fa-print me-2"></i>Print</button>';
                    $btn .= '</div>';

                    return $btn;
                    
                })            
                ->rawColumns(['action'])
                ->make(true);

    }

    public function adjustment_saleperson(Request $request)
    {

        if ($request->ajax()) {
            
            $data = DB::table('users as user')
            ->join('commission', 'commission.seller_id', '=', 'user.user_id')
            ->select('user.*', 'commission.*', DB::raw('SUM(commission.amount) as total_sales'))
            ->where('user.user_role', '!=', '1')
            ->where('user.user_status', '=', '1')
            ->whereBetween('commission.commission_added_on', [
                date('Y-m-d 00:00:00', strtotime($request->start_date)),
                date('Y-m-d 23:59:59', strtotime($request->end_date))
            ])
            ->groupBy('user.user_id')
            ->orderBy('user.user_name', 'asc')
            ->get();
                    
            return response()->json(['status'=>'success','seleperson'=>$data]);
        }    
    }

    public function get_salary(Request $request)
    {        
        // $currentMonth = Carbon::now()->format('Y-m');
        
        $sellerId = $request->seller_id; 

        $seller = DB::table('commission')
            ->join('users as user', 'commission.seller_id', '=', 'user.user_id') 
            ->select(
                DB::raw('DATE(commission.commission_added_on) as date'),
                DB::raw('SUM(commission.amount) as total_sales'),
                'user.*',
                'commission.commission_id'
            )
            // ->where('commission.commission_added_on', 'like', $currentMonth . '%')
            ->where('commission.seller_id', $sellerId)         
            ->groupBy(DB::raw('DATE(commission.commission_added_on)'))
            ->whereBetween('commission.commission_added_on', [
                date('Y-m-d 00:00:00', strtotime($request->start_date)),
                date('Y-m-d 23:59:59', strtotime($request->end_date))
            ])
            ->orderBy('commission.commission_added_on', 'asc')
            ->get();
    
        return Datatables::of($seller)
        ->addIndexColumn()              
        ->addColumn('date',function($row){
            return $row->date;
        })
        ->addColumn('total_sales',function($row){
            return '$'.number_format((float)$row->total_sales, 2, '.', '');
            
        })
        ->addColumn('total_commission',function($row){

            if ($row->user_salary_type == 1) {                        
                return "$".number_format((float)'0',2);
            }
            if ($row->user_salary_type == 2) {
                
               $total_commission = $row->total_sales*$row->user_commission/100;
               return '$'.number_format((float)$total_commission,2);
            }                    
            if ($row->user_salary_type == 3) {
                
                $userCommission = json_decode($row->user_commission, true);
            
                $from = $userCommission['FROM'];
                $to = $userCommission['TO'];
                $amount = $userCommission['AMOUNT'];
            
                $totalCommission = 0;

                $salesRecords = DB::table('commission')->where('seller_id', $row->user_id)->get();
                
                $dailySales = $row->total_sales;
                    
                $calculatedCommission = 0;
    
                    foreach ($from as $step => $minValue) {
                        $maxValue = $to[$step];
                        $commissionRate = $amount[$step];
    
                        if ($dailySales >= $minValue && $dailySales <= $maxValue) {
                            $calculatedCommission = number_format(floor($dailySales * $commissionRate) / 100,2);
                            break; 
                        }
                    }
                    
                $totalCommission += $calculatedCommission; 

                return "$".number_format((float)$totalCommission,2);
            }
         
        })
        ->addColumn('bonus',function($row) use($request){
                
            $commissionId = $row->commission_id;
            $bonus = Bonus::where('commission_id',$commissionId)
            ->whereBetween('bonus.bonus_added_on', [
                date('Y-m-d 00:00:00', strtotime($request->start_date)),
                date('Y-m-d 23:59:59', strtotime($request->end_date))
            ])->get();
       
            if ($bonus->isNotEmpty()) {
            
                foreach ($bonus as $key => $value) 
                {
                    $bonus = $value->bonus;
                }
                $formattedBonus = number_format((float)$bonus, 2);

                $bonusHTML = "<input type='text' data-commission_id_input='".$row->commission_id."' class='editBonusInput bonusInput' readonly value='$" .$formattedBonus. "' />
                <br/>
                 <small class='text-danger bonusError' style='display: none;'></small> ";                

                return $bonusHTML;
            } 
            else{
                return "$0.00";
            }

        })
        ->addColumn('total_salary',function($row) use($request){
                  
                    $commissionId = $row->commission_id;
                    $bonus = Bonus::where('commission_id',$commissionId)
                    ->whereBetween('bonus.bonus_added_on', [
                        date('Y-m-d 00:00:00', strtotime($request->start_date)),
                        date('Y-m-d 23:59:59', strtotime($request->end_date))
                    ])->get();
                    $bonusAmount=0;
                    if ($bonus->isNotEmpty()) {
                    
                        foreach ($bonus as $key => $value) 
                        {
                            $bonusAmount = $value->bonus;
                        }                     
                    }
                    $bonusAmount = $bonusAmount;

                    // Calculate total commission based on salary type
                    $total_commission = 0;
                    if ($row->user_salary_type == 1) {
                        $total_commission = 0;
                    } elseif ($row->user_salary_type == 2) {
                        $total_commission = $row->total_sales * $row->user_commission / 100;
                    } elseif ($row->user_salary_type == 3) {
                        $userCommission = json_decode($row->user_commission, true);
                        $from = $userCommission['FROM'];
                        $to = $userCommission['TO'];
                        $amount = $userCommission['AMOUNT'];
                
                        $dailySales = $row->total_sales;
                        $calculatedCommission = 0;
                
                        foreach ($from as $step => $minValue) {
                            $maxValue = $to[$step];
                            $commissionRate = $amount[$step];
                
                            if ($dailySales >= $minValue && $dailySales <= $maxValue) {
                                $calculatedCommission = number_format(floor($dailySales * $commissionRate) / 100, 2);
                                break;
                            }
                        }
                
                        $total_commission = $calculatedCommission;
                    }
                
                    $total_salary = $total_commission + $bonusAmount;
                
                    return '$' . number_format($total_salary, 2);
        
        })
        ->addColumn('action', function($row){
        
            $bonus = Bonus::where('commission_id', $row->commission_id)->first();
  
            $btn = '<div class="hstack gap-2 fs-15 justify-content-center">';                    
            if ($bonus) {
                $btn .= '<button type="button" data-seller_id="'.$row->user_id.'"  data-commission_date="'.$row->date.'" data-commission_id="'.$row->commission_id.'" class="btn btn-sm btn-warning edit_bonus current_seller_id "><i class="fas fa-edit me-2"></i>Edit Bonus</button>';
                $btn .= '<button type="button" style="display:none" data-seller_id="'.$row->user_id.'"  data-commission_date="'.$row->date.'" data-commission_id="'.$row->commission_id.'" class="btn btn-sm btn-warning update_bonus current_seller_id "><i class="fa-solid fa-check  me-2"></i>Update</button>';
                // $btn .= '<button type="button" data-seller_id="'.$row->user_id.'"  data-commission_date="'.$row->date.'" data-commission_id="'.$row->commission_id.'" class="btn btn-sm btn-danger current_seller_id" id="delete_bonus"> <i class="fa-solid fa-xmark me-2"></i>Remove</button>';
            }  
            $btn .= '</div>';
            return $btn;            
        })
        ->rawColumns(['action','bonus'])
        ->make(true);
    }

    public function get_adjustment(Request $request)
    {
      
        $seller_id = $request->seller_id;
        
        $seller = DB::table('users as user')
        ->join('commission', 'commission.seller_id', '=', 'user.user_id')
        ->select('user.*', 'commission.*')
        ->where('user.user_role', '!=', '1')
        ->where('user.user_status', '=', '1')
        ->where('user.user_id', '=', $seller_id)      
        ->get();

        $adjustments = Adjustment::whereHas('adjustment_saleperson', function ($query) use ($seller_id) {
            $query->where('seller_id', $seller_id);
        })
        ->whereBetween('date', [
            date('Y-m-d', strtotime($request->start_date)),
            date('Y-m-d', strtotime($request->end_date))
        ])
        ->orderBy('adjustments.date', 'asc')
        ->get();

        $reimbursement_TotalAmount = 0;
        $deduction_TotalAmount = 0;
        foreach ($adjustments as $key => $value) 
        { 
            if ($value->type == 'reimbursements') {
                $reimbursement_TotalAmount += $value->reimbursement_deduction_amount;                        
            }
            if ($value->type == 'deductions') {
                
                $deduction_TotalAmount += $value->reimbursement_deduction_amount;
            }
        }
        $reimbursement_TotalAmount = $reimbursement_TotalAmount ;
        $deduction_TotalAmount = $deduction_TotalAmount;


        return response()->json(['status'=>'success','seller'=>$seller,'adjustments'=>$adjustments,'reimbursement_TotalAmount'=>$reimbursement_TotalAmount,'deduction_TotalAmount'=>$deduction_TotalAmount]);

    }

    public function adjustment(Request $request)
    {
        if($request->has('submit'))
        {            
            $adjustment = new Adjustment();
            $adjustment->type =  $request->type;
            $adjustment->date = Carbon::createFromFormat('d-m-Y', $request->input('date'))->format('Y-m-d'); 
            $adjustment->reimbursement_deduction_amount =  number_format((float)$request->reimbursement_deduction_amount, 2, '.', ''); 
            $adjustment->description =  $request->description;
            $adjustment->adjustment_added_on =  date('Y-m-d H:i:s');
            $adjustment->adjustment_updated_on =  date('Y-m-d H:i:s');
            // dd($adjustment);
            $adjustment->save();

            foreach ($request->salespersons as $key => $value) {
            
                $adjustmentSaleperson = new AdjustmentSaleperson();
                $adjustmentSaleperson->adjustment_id = $adjustment->adjustment_id;
                $adjustmentSaleperson->seller_id  = $value;
                $adjustmentSaleperson->save();
            }
        }

        return redirect()->route('admin.salary')->with('success', 'Adjustment Saleperson Created Successfully');
    }

    // public function getBonus(Request $request)
    // {
    //     $commission_id = $request->commission_id;

    //     $bonus = Bonus::where('commission_id', $commission_id)->first();

    //     if ($bonus) {
    //         return response()->json([
    //             'status' => 'success',
    //             'bonus' => $bonus->bonus,
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'No bonus record found.',
    //         ]);
    //     }
    // }

    public function bonus(Request $request) {
        if ($request->ajax()) {
            
            $bonus = Bonus::where('commission_id', $request->commission_id)->first();
    
            if ($bonus) {
                $bonus->bonus = number_format((float)$request->bonus, 2, '.', ''); 
                // $bonus->commission_date = $request->commission_date; 
                $bonus->bonus_updated_on = date('Y-m-d H:i:s');
                // dd($bonus);
                $bonus->update();    
                
                return response()->json(['status' => 'success', 'message' => 'Bonus updated successfully','bonus' => $bonus->bonus,'commission_id'=>$bonus->commission_id]);
            } else {
                $bonus = new Bonus();            
                $bonus->bonus = number_format((float)$request->bonus, 2, '.', ''); 
                $bonus->seller_id = $request->seller_id; 
                $bonus->commission_id = $request->commission_id; 
                $bonus->commission_date = $request->commission_date; 
                $bonus->bonus_added_on = date('Y-m-d H:i:s');
                $bonus->bonus_updated_on = date('Y-m-d H:i:s');
                $bonus->save();
    
                if ($bonus) {
                    return response()->json(['status' => 'success', 'message' => 'Bonus added successfully']);
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Failed to add bonus']);
                }
            }
        }
    }

    public function delete_bonus(Request $request)
    {
        $commission_id = $request->commission_id;

        $bonus = Bonus::where('commission_id', $commission_id)->first();

        if ($bonus) {
            $bonus->bonus = 0;
            $bonus->update();
            return response()->json([
                'status' => 'success',
                'message' => 'Bonus deleted successfully.',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No bonus record found.',
            ]);
        }
    }

    
}