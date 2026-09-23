@extends('layouts.public')
@section('title', 'Tributes')
@section('content')

<section class="tributes" id="tributes">
  <div class="container">
    <div class="section-label">Remembrance Wall</div>
    <h2 class="section-title">Share a Tribute or Memory</h2>
    <div class="rule"></div>
    <div class="tribute-layout">
      <form class="tribute-form" method="POST" action="{{ route('tributes.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">
        <div class="field">
          <label for="tName">Your name</label>
          <input type="text" id="tName" name="name" required value="{{ old('name') }}">
        </div>
        <div class="field">
          <label for="tRel">Relationship to {{ $memorial->title }} {{ $memorial->name }}</label>
          <input type="text" id="tRel" name="relationship" placeholder="e.g. Church member, Colleague, Friend" value="{{ old('relationship') }}">
        </div>
        <div class="field">
          <label for="tMsg">Your message</label>
          <textarea id="tMsg" name="message" required placeholder="Share a memory">{{ old('message') }}</textarea>
        </div>
        <div class="field">
          <label for="tImg">Photo (optional)</label>
          <input type="file" id="tImg" name="image" accept="image/*">
        </div>
        <div class="consent">
          <input type="checkbox" id="tConsent" name="publication_consent" required>
          <label for="tConsent">I consent to my name and message being reviewed and published on this memorial page.</label>
        </div>
        <button type="submit" class="submit-btn">Submit Tribute</button>
      </form>

      <div class="tribute-carousel-col">
        <div class="tribute-carousel" data-carousel>
          <div class="carousel-viewport">
            <div class="carousel-track" data-track>
              @forelse($tributes as $tribute)
              <div class="carousel-slide" data-slide>
                <span class="carousel-quote-mark" aria-hidden="true">&ldquo;</span>
                @if($tribute->imageUrl())
                  <img src="{{ $tribute->imageUrl() }}" alt="Photo shared by {{ $tribute->name }}" class="carousel-photo">
                @endif
                <p class="carousel-msg">{{ $tribute->message }}</p>
                <div class="carousel-who">
                  {{ $tribute->name }}
                  @if($tribute->relationship)
                    <span class="carousel-rel">{{ $tribute->relationship }}</span>
                  @endif
                </div>
              </div>
              @empty
              <div class="carousel-slide carousel-empty" data-slide>
                <p>Approved tributes will appear here.</p>
              </div>
              @endforelse
            </div>
          </div>

          @if($tributes->count() > 1)
          <div class="carousel-controls">
            <button type="button" class="carousel-arrow" data-prev aria-label="Previous tribute">&#8249;</button>
            <div class="carousel-dots" data-dots></div>
            <button type="button" class="carousel-arrow" data-next aria-label="Next tribute">&#8250;</button>
          </div>
          @endif
        </div>

        <div class="pagination">{{ $tributes->links() }}</div>
      </div>
    </div>
  </div>
</section>

<style>
/* Flexbox layout: reliable equal-height stretch across both columns,
   with the form given a larger share of the row than before. */
.tributes {
  padding: 56px 0;
}

.tributes .section-title {
  margin-bottom: 24px;
}

.tributes .rule {
  margin-bottom: 20px;
}

.tribute-layout {
  display: flex;
  flex-wrap: wrap;
  align-items: stretch;
  gap: 40px;
}

.tribute-form {
  flex: 1.15 1 380px;
  padding: 26px;
}

.tribute-form .field {
  margin-bottom: 16px;
}

.tribute-form .field label {
  font-size: 16px;
}

.tribute-form .field input,
.tribute-form .field textarea {
  font-size: 19px;
}

.tribute-form .consent {
  font-size: 15px;
  margin-bottom: 18px;
}

.tribute-form .submit-btn {
  font-size: 19px;
}

.tribute-carousel-col {
  flex: 1 1 380px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.tribute-carousel {
  position: relative;
  background: linear-gradient(180deg, #fdfbf7 0%, #f7f2ea 100%);
  border: 1px solid rgba(169, 139, 79, 0.25);
  border-radius: 4px;
  box-shadow: 0 18px 40px -20px rgba(30, 25, 15, 0.35);
  padding: 32px 30px 24px;
  flex: 1;
  min-height: 0;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.carousel-viewport {
  overflow: hidden;
  position: relative;
  flex: 1;
  min-height: 0;
}

.carousel-track {
  display: flex;
  transition: transform 0.55s cubic-bezier(0.65, 0, 0.35, 1);
  height: 100%;
}

.carousel-slide {
  flex: 0 0 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 0 8px;
  opacity: 0;
  transition: opacity 0.4s ease;
}

.carousel-slide.is-active {
  opacity: 1;
}

.carousel-quote-mark {
  font-family: 'Cormorant Garamond', serif;
  font-size: 64px;
  line-height: 1;
  color: #a98b4f;
  margin-bottom: -6px;
}

.carousel-photo {
  width: 96px;
  height: 96px;
  object-fit: cover;
  border-radius: 50%;
  margin: 8px 0 18px;
  box-shadow: 0 8px 20px -8px rgba(0, 0, 0, 0.35);
  border: 3px solid #fdfbf7;
}

.carousel-msg {
  font-family: 'EB Garamond', serif;
  font-size: 20px;
  line-height: 1.65;
  color: #33291b;
  font-style: italic;
  max-width: 480px;
  margin: 0 auto 20px;
}

.carousel-who {
  font-family: 'Cormorant Garamond', serif;
  font-size: 16px;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  color: #a98b4f;
  font-weight: 600;
}

.carousel-rel {
  display: block;
  margin-top: 4px;
  font-family: 'EB Garamond', serif;
  text-transform: none;
  letter-spacing: 0;
  font-style: italic;
  color: #7a6a4f;
  font-size: 15px;
}

.carousel-empty p {
  font-family: 'EB Garamond', serif;
  font-style: italic;
  color: #8a7a5f;
  margin: auto;
}

.carousel-controls {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 18px;
  margin-top: 20px;
}

.carousel-arrow {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: 1px solid rgba(169, 139, 79, 0.4);
  background: transparent;
  color: #a98b4f;
  font-size: 20px;
  line-height: 1;
  cursor: pointer;
  transition: background 0.25s ease, color 0.25s ease;
}

.carousel-arrow:hover {
  background: #a98b4f;
  color: #fdfbf7;
}

.carousel-dots {
  display: flex;
  gap: 8px;
}

.carousel-dots button {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  border: none;
  background: rgba(169, 139, 79, 0.3);
  cursor: pointer;
  padding: 0;
  transition: background 0.25s ease, transform 0.25s ease;
}

.carousel-dots button.is-active {
  background: #a98b4f;
  transform: scale(1.25);
}

@media (max-width: 768px) {
  .tribute-layout {
    flex-direction: column;
  }

  .tribute-form,
  .tribute-carousel-col {
    flex: none;
  }

  .tribute-carousel {
    padding: 32px 24px 24px;
    min-height: 340px;
    flex: none;
  }
}
</style>

<script>
document.querySelectorAll('[data-carousel]').forEach(function (root) {
  var track = root.querySelector('[data-track]');
  var slides = Array.prototype.slice.call(root.querySelectorAll('[data-slide]'));
  if (!slides.length) return;

  var dotsWrap = root.querySelector('[data-dots]');
  var prevBtn = root.querySelector('[data-prev]');
  var nextBtn = root.querySelector('[data-next]');
  var index = 0;
  var timer = null;

  if (dotsWrap) {
    slides.forEach(function (_, i) {
      var dot = document.createElement('button');
      dot.type = 'button';
      dot.setAttribute('aria-label', 'Go to tribute ' + (i + 1));
      dot.addEventListener('click', function () { goTo(i); });
      dotsWrap.appendChild(dot);
    });
  }

  function render() {
    track.style.transform = 'translateX(-' + (index * 100) + '%)';
    slides.forEach(function (slide, i) {
      slide.classList.toggle('is-active', i === index);
    });
    if (dotsWrap) {
      Array.prototype.forEach.call(dotsWrap.children, function (dot, i) {
        dot.classList.toggle('is-active', i === index);
      });
    }
  }

  function goTo(i) {
    index = (i + slides.length) % slides.length;
    render();
  }

  function next() { goTo(index + 1); }
  function prev() { goTo(index - 1); }

  function startAutoplay() {
    stopAutoplay();
    if (slides.length > 1) timer = setInterval(next, 6000);
  }
  function stopAutoplay() {
    if (timer) clearInterval(timer);
  }

  if (nextBtn) nextBtn.addEventListener('click', function () { next(); startAutoplay(); });
  if (prevBtn) prevBtn.addEventListener('click', function () { prev(); startAutoplay(); });

  root.addEventListener('mouseenter', stopAutoplay);
  root.addEventListener('mouseleave', startAutoplay);

  render();
  startAutoplay();
});
</script>
@endsection
