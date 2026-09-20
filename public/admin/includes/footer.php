  </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  if (document.querySelector('.richtext-editor')) {
    tinymce.init({
      selector: '.richtext-editor',
      height: 320,
      menubar: false,
      plugins: 'lists link',
      toolbar: 'bold italic underline | bullist numlist | link | removeformat'
    });
  }
</script>
</body>
</html>
