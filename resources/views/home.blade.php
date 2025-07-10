@extends('welcome')


@section('home')

<!-- Home page content -->

  <div class="container py-5">
    <h2 class="text-center mb-4">URL shortener</h2>

    <div id="alert-area"></div>

    <form id="shorten-form" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="url" class="form-label">URL to shorten</label>
            <input type="url" class="form-control" id="url" name="url" required placeholder="https://example.com">
        </div>
        <div class="mb-3">
            <label for="custom_code" class="form-label">Custom code (optional)</label>
            <input type="text" class="form-control" id="custom_code" name="custom_code" placeholder="ex: mycode">
        </div>
        <div class="mb-3">
            <label for="expires_at" class="form-label">Expiration (optional)</label>
            <input type="datetime-local" class="form-control" id="expires_at" name="expires_at">
        </div>
        <button type="submit" class="btn btn-primary w-100">Shorten</button>
    </form>

    <div id="result" class="mt-4 d-none">
        <div class="alert alert-success">
            Shortcut link: <a href="#" target="_blank" id="short-url"></a>
        </div>
    </div>
</div>
<!-- Form script -->
<script>
    // Handle form submission
    document.getElementById('shorten-form').addEventListener('submit', async function (e) {
        // Clear previous results
        e.preventDefault();
        // Get form values
        const url = document.getElementById('url').value;
        const customCode = document.getElementById('custom_code').value;
        const expiresAt = document.getElementById('expires_at').value;
        // Validate URL
        const response = await fetch('/api/shorten', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                url: url,
                custom_code: customCode || undefined,
                expires_at: expiresAt || undefined
            })
        });
        // Handle response
        const resultDiv = document.getElementById('result');
        const alertArea = document.getElementById('alert-area');
        alertArea.innerHTML = '';
        resultDiv.classList.add('d-none');
        // Clear previous short URL
        // Reset the short URL display
        if (response.ok) {
            const data = await response.json();
            document.getElementById('short-url').textContent = data.short_url;
            document.getElementById('short-url').href = data.short_url;
            resultDiv.classList.remove('d-none');
        } else {
            const errorData = await response.json();
            let messages = '';
            if (errorData.errors) {
                for (let field in errorData.errors) {
                    messages += `<li>${errorData.errors[field][0]}</li>`;
                }
            } else {
                messages = `<li>${errorData.message}</li>`;
            }
            alertArea.innerHTML = `
                <div class="alert alert-danger">
                    <ul class="mb-0">${messages}</ul>
                </div>
            `;
        }
        
    });
</script>
<style>
    [type=button], [type=reset], [type=submit], button {
    -webkit-appearance: button;
    background-color: #0d6efd;
    background-image: none;
}
</style>
@stop