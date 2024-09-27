@csrf
<input type="hidden" name="category_id" value="{{ $category->category_id }}">
<div class="mb-3">
    <label class="form-label">Category Name</label>
    <input type="text" name="category_name" class="form-control" value="{{ $category->category_name }}" autocomplete="off">
    @if($errors->has('category_name'))
    <small class="text-danger">{{ $errors->first('category_name') }}</small>
    @endif
</div>
<div class="modal-footer-btn">
    <button type="submit" name="submit" class="btn btn-submit" value="submit">Submit</button>
</div>
<script>
edit_category_image.onchange = evt => {
  const [file] = edit_category_image.files
  if (file) {
    edit_category_image_src.src = URL.createObjectURL(file)
  }
}
</script>