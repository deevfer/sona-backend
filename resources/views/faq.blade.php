<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }} | FAQ</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('assets/sonaFavicon.svg') }}">
        <link rel="alternate icon" href="{{ asset('assets/sonaFavicon.svg') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700" rel="stylesheet" />
        <script defer src="https://unpkg.com/i18next@23.11.5/i18next.min.js"></script>
        <script defer src="{{ asset('js/translations.js') }}"></script>

        @vite(['resources/css/app.css','resources/css/all.css', 'resources/js/app.js'])
    </head>
    <body>
        <section class="header">
            <div class="container">
                <div class="headerContent">
                    <div class="item">
                        <a href="/">
                            <img src="{{ asset('assets/sonaLogoAnimated.svg') }}" alt="Sona Logo">
                        </a>
                    </div>


                    <div class="item">
                        <div class="lang">
                            <button class="langBtn active" data-lang="en" onclick="changeLanguage('en')">EN</button>
                            <span>|</span>
                            <button class="langBtn" data-lang="es" onclick="changeLanguage('es')">ES</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container">
                <div class="requirements">
                    <h1 data-i18n="faq_title">FAQ</h1>
                    <div class="faq">
                        <!-- ITEM -->
                        <div class="faqItem active">
                            <button class="faqQuestion" data-i18n="faq_q1">
                                What is Sona?
                            </button>
                            <div class="faqAnswer" data-i18n="faq_a1">
                                Sona is an app that lets you rediscover your music through a visual experience inspired by vinyl records, connecting to services like Apple Music and, coming soon, Spotify (iOS) and any music streaming app (Android).
                            </div>
                        </div>

                        <div class="faqItem">
                            <button class="faqQuestion" data-i18n="faq_q2">Is Sona a music service?</button>
                            <div class="faqAnswer" data-i18n="faq_a2">
                                No. Sona does not store or distribute music. It acts as an interface that connects to your streaming service to play your library.
                            </div>
                        </div>

                        <div class="faqItem">
                            <button class="faqQuestion" data-i18n="faq_q3">What do I need to use Sona?</button>
                            <div class="faqAnswer" data-i18n="faq_a3">
                                You need a compatible device, an internet connection, a Sona account, and an active subscription to Apple Music or, coming soon, Spotify Premium (iOS) and any music streaming app (Android).
                            </div>
                        </div>

                        <div class="faqItem">
                            <button class="faqQuestion" data-i18n="faq_q4">Does Sona work without a subscription?</button>
                            <div class="faqAnswer" data-i18n="faq_a4">
                                No. Sona requires an active subscription to a compatible streaming service to play music.
                            </div>
                        </div>

                        <div class="faqItem">
                            <button class="faqQuestion" data-i18n="faq_q5">Is Spotify available yet?</button>
                            <div class="faqAnswer" data-i18n="faq_a5">
                                Sona is currently compatible with Apple Music. Spotify Premium integration is coming soon (iOS) and any music streaming app (Android).
                            </div>
                        </div>

                        <div class="faqItem">
                            <button class="faqQuestion" data-i18n="faq_q6">On which devices can I use Sona?</button>
                            <div class="faqAnswer" data-i18n="faq_a6">
                                Sona is available on iPhone, iPad, Android devices, tablets, and computers via modern browsers.
                            </div>
                        </div>

                        <div class="faqItem">
                            <button class="faqQuestion" data-i18n="faq_q7">How does payment work?</button>
                            <div class="faqAnswer" data-i18n="faq_a7">
                                Sona operates on a one-time payment basis, giving you lifetime access to the app.
                            </div>
                        </div>

                        <div class="faqItem">
                            <button class="faqQuestion" data-i18n="faq_q8">Is it a subscription?</button>
                            <div class="faqAnswer" data-i18n="faq_a8">
                                No. The payment is a one-time fee; there are no recurring charges.
                            </div>
                        </div>

                        <div class="faqItem">
                            <button class="faqQuestion" data-i18n="faq_q9">Does it include future updates?</button>
                            <div class="faqAnswer" data-i18n="faq_a9">
                                Yes. Your payment includes access to future improvements at no additional cost.
                            </div>
                        </div>

                        <div class="faqItem">
                            <button class="faqQuestion" data-i18n="faq_q10">Can I use Sona on multiple devices?</button>
                            <div class="faqAnswer" data-i18n="faq_a10">
                                Yes, you can access your account from different compatible devices.
                            </div>
                        </div>

                        <div class="faqItem">
                            <button class="faqQuestion" data-i18n="faq_q11">Does Sona store my music?</button>
                            <div class="faqAnswer" data-i18n="faq_a11">
                                No. All music is streamed directly from your connected service.
                            </div>
                        </div>

                        <div class="faqItem">
                            <button class="faqQuestion" data-i18n="faq_q12">Does Sona work offline?</button>
                            <div class="faqAnswer" data-i18n="faq_a12">
                                No. An internet connection is required to play music.
                            </div>
                        </div>

                        <div class="faqItem">
                            <button class="faqQuestion" data-i18n="faq_q13">Can I get a refund?</button>
                            <div class="faqAnswer" data-i18n="faq_a13">
                                Payments are non-refundable except where required by law.
                            </div>
                        </div>

                        <div class="faqItem">
                            <button class="faqQuestion" data-i18n="faq_q14">Will Sona keep improving?</button>
                            <div class="faqAnswer" data-i18n="faq_a14">
                                Yes. Sona is constantly evolving to improve the experience.
                            </div>
                        </div>

                        <div class="faqItem">
                            <button class="faqQuestion" data-i18n="faq_q15">Where can I get support?</button>
                            <div class="faqAnswer" data-i18n="faq_a15">
                                Contact us at: sona@fernandovasquez.tech
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <section class="footer">
            <div class="container">
                <div class="footerContent">
                    <div class="itemFooter">
                        <p>© {{ date('Y') }}</p>
                    </div>
                    <div class="itemFooter">
                        <p data-i18n="developed">Developed by <a href="https://www.tiktok.com/@devfer94" target="_blank">@devfer94</a></p>
                    </div>
                    <div class="itemFooter">
                        <a href="/">
                            <img src="{{ asset('assets/sonaLogoAnimated.svg') }}" alt="Sona Logo">
                        </a>
                    </div>
                    <div class="itemFooter">
                        <a href="/terms" data-i18n="terms">Terms and conditions</a>
                    </div>
                    <div class="itemFooter">
                        <a href="/faq" data-i18n="faq">FAQ</a>
                    </div>
                </div>
            </div>
        </section>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const items = document.querySelectorAll('.faqItem');

                items.forEach(item => {
                    const btn = item.querySelector('.faqQuestion');

                    btn.addEventListener('click', () => {
                        items.forEach(i => i.classList.remove('active'));
                        item.classList.add('active');
                    });
                });
            });
        </script>
    </body>
</html>