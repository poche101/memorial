<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex">
<title>Downloading Brochure &mdash; {{ $memorial->title ?? '' }} {{ $memorial->name }}</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;1,400&display=swap" rel="stylesheet">
{{-- Fallback if JavaScript is disabled --}}
<noscript><meta http-equiv="refresh" content="6;url={{ route('home') }}"></noscript>
<style>
  body {
    margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center;
    background: #0f1b2f; color: #f4e6bd; font-family: 'Cormorant Garamond', serif;
    text-align: center; padding: 24px;
  }
  .card { max-width: 420px; }
  h1 { font-weight: 500; font-size: 1.8rem; margin: 0 0 .5rem; }
  p  { font-style: italic; opacity: .85; margin: .4rem 0; }
  a.btn {
    display: inline-block; margin-top: 1.2rem; padding: .7rem 1.4rem; border: 1px solid #a9863f;
    color: #f4e6bd; text-decoration: none; border-radius: 4px;
  }
  small { display: block; margin-top: 1.4rem; opacity: .6; }
</style>
</head>
<body>
  <div class="card">
    <h1>In Loving Memory of {{ $memorial->title ?? '' }} {{ $memorial->name }}</h1>
    <p>Your brochure is downloading&hellip;</p>
    <a class="btn" href="{{ route('brochure.download') }}">Download again</a>
    <small>You will be taken to the memorial site in a moment.</small>
  </div>

  {{-- Hidden frame starts the download without leaving this page --}}
  <iframe id="dl" style="display:none" aria-hidden="true"></iframe>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.getElementById('dl').src = @json(route('brochure.download'));
      setTimeout(function () {
        window.location.href = @json(route('home'));
      }, 4000);
    });
  </script>
</body>
</html>
