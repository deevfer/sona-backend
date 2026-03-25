<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }} | HOME</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('assets/sonaFavicon.svg') }}">
        <link rel="alternate icon" href="{{ asset('assets/sonaFavicon.svg') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700" rel="stylesheet" />
        <script defer src="https://unpkg.com/i18next@23.11.5/i18next.min.js"></script>
        <script defer src="{{ asset('js/translations.js') }}"></script>

        @vite(['resources/css/app.css','resources/css/all.css', 'resources/js/app.js'])
    </head>
    <body>
        <section class="header desktop">
            <div class="container">
                <div class="headerContent">
                    <div class="item">
                        <a href="/">
                            <img src="{{ asset('assets/sonaLogoAnimated.svg') }}" alt="Sona Logo">
                        </a>
                    </div>

                    <div class="item">
                        <div class="tabs">
                            <div class="tabIndicator"></div>
                            <button class="tabBtn active" data-tab="application" data-i18n="application">Application</button>
                            <button class="tabBtn" data-tab="requirements" data-i18n="requirements">Requirements</button>
                        </div>
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
        <section class="header responsive">
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
                <div class="headerTabs">

                    <div class="item">
                        <div class="tabs">
                            <div class="tabIndicator"></div>
                            <button class="tabBtn active" data-tab="application" data-i18n="application">Application</button>
                            <button class="tabBtn" data-tab="requirements" data-i18n="requirements">Requirements</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container">
                <div id="application" class="tabContent active">
                    <div class="appContent">
                        <h1 data-i18n="application_text">Rediscover your favorite music on vinyl.</h1>
                    </div>
                    <div class="vinilos">
                        <div class="stack3D fadeIn" id="vinylStack">
                            @foreach($albums as $index => $album)
                                <div class="album3D" style="--idx: {{ $index }}">
                                    <div class="albumBox">
                                        <div class="albumFront">
                                            <img src="{{ $album['image'] }}" alt="{{ $album['title'] }}">
                                        </div>
                                        <div class="albumBack">
                                            <img src="{{ $album['image'] }}" alt="{{ $album['title'] }}">
                                        </div>
                                        <div class="albumSpine">
                                            <span>{{ $album['title'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="available">
                        <div class="availableBar"></div>
                        <div class="availableContent">
                            <h1 data-i18n="available_title">Available on</h1>
                            <div class="devices">
                                <span>iPhone</span>
                                <span>iPad</span>
                                <span>Android</span>
                                <span>Tablets</span>
                                <span>Web</span>
                            </div>
                            <div class="downloadButtons">
                                <a href=""><img src="{{ asset('assets/appstore.svg') }}" alt="App Store Button"></a>
                                <a href="{{ asset('downloads/Sona.apk') }}" download="Sona.apk"><img src="{{ asset('assets/googleplay.svg') }}" alt="Google Play Button"></a>
                            </div>
                            <div class="registerOnWeb">
                                <div class="bar"></div>
                                <span data-i18n="or_text">OR</span>
                                <div class="bar"></div>
                            </div>
                            <div class="registerOnWebButton">
                                <div class="buttonWeb">
                                    <a href="https://sona-studio.fernandovasquez.tech/" target="_blank" data-i18n="register_on_web">Register on web</a>
                                </div>
                                <div class="badge">
                                    <p>-25% <span>off</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="requirements" class="tabContent requirements">
                    <h1 data-i18n="requirements_title">Requirements</h1>
                    <div class="requirements">
                        <h3 data-i18n="req_devices_title">Compatible Devices</h3>
                        <p data-i18n="req_devices_text">
                            Sona is available on iPhone, iPad, Android devices, tablets, and computers via a web browser.
                        </p>

                        <h3 data-i18n="req_os_title">Compatible operating systems</h3>
                        <p data-i18n="req_os_text">
                            iPhone: iOS 16 or later<br>
                            iPad: iPadOS 16 or later<br>
                            Android (smartphones and tablets): Android 10 or later<br>
                            Web/Desktop: Up-to-date browser (Chrome, Safari, or Edge recommended)
                        </p>

                        <h3 data-i18n="req_music_title">Required music service</h3>
                        <p data-i18n="req_music_text">
                            To use Sona, you need an active subscription to a compatible service:<br>
                            Apple Music (currently available)<br>
                            Spotify Premium (coming soon)
                        </p>

                        <h3 data-i18n="req_account_title">Account and access</h3>
                        <p data-i18n="req_account_text">
                            You must create a Sona account, sign in on your device, and link your compatible music service.
                        </p>

                        <h3 data-i18n="req_internet_title">Internet connection</h3>
                        <p data-i18n="req_internet_text">
                            An internet connection is required to sign in, sync your library, and play music from your connected service.
                        </p>

                        <h3 data-i18n="req_permissions_title">Permissions</h3>
                        <p data-i18n="req_permissions_text">
                            You must authorize Sona to connect to your music service and allow audio playback on the device.
                        </p>

                        <h3 data-i18n="req_access_title">Access to Sona</h3>
                        <p data-i18n="req_access_text">
                            Sona operates on a one-time payment basis, granting lifetime access to the full experience.
                        </p>

                        <h3 data-i18n="req_notes_title">Important Notes</h3>
                        <p data-i18n="req_notes_text">
                            Sona does not store music; it plays content from your streaming service.<br>
                            Some features may depend on your active music provider.<br>
                            Spotify will be available soon.<br>
                            The experience may vary slightly depending on your device, browser, or operating system version.
                        </p>
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
            const tabButtons = document.querySelectorAll('.tabBtn');
            const tabContents = document.querySelectorAll('.tabContent');
            const tabGroups = document.querySelectorAll('.tabs');

            function setActiveTab(target) {
                tabButtons.forEach(btn => {
                    btn.classList.toggle('active', btn.getAttribute('data-tab') === target);
                });

                tabContents.forEach(content => {
                    content.classList.toggle('active', content.id === target);
                });

                tabGroups.forEach(group => {
                    const buttons = group.querySelectorAll('.tabBtn');
                    const indicator = group.querySelector('.tabIndicator');

                    const activeIndex = [...buttons].findIndex(
                        btn => btn.getAttribute('data-tab') === target
                    );

                    if (indicator && activeIndex >= 0) {
                        indicator.style.transform = `translateX(${activeIndex * 100}%)`;
                    }
                });
            }

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const target = button.getAttribute('data-tab');
                    setActiveTab(target);
                });
            });

            document.addEventListener('DOMContentLoaded', () => {
                const initiallyActive = document.querySelector('.tabBtn.active')?.getAttribute('data-tab') || 'application';
                setActiveTab(initiallyActive);
            });
        </script>
        <script>
        const vinylStack = document.getElementById('vinylStack');
        let scrollLeft = 0;

        function getAlbumStyle(index) {
            const albumW = 450;
            const overlap = 360;
            const spacing = albumW - overlap;
            const albumCenter = index * spacing + albumW / 2;

            const containerW = vinylStack?.clientWidth || window.innerWidth;
            const viewCenter = scrollLeft + containerW / 2;
            const dist = albumCenter - viewCenter;
            const maxDist = containerW * 0.6;
            const norm = Math.max(-1, Math.min(1, dist / maxDist));

            const angle = 90 + norm * 40;
            const zi = Math.round((1 - Math.abs(norm)) * 100);

            return {
                '--rot': `${angle}deg`,
                zIndex: Math.max(zi, 0),
            };
        }

        function updateStackStyles() {
            const albums = vinylStack.querySelectorAll('.album3D');
            albums.forEach((album, index) => {
                const style = getAlbumStyle(index);
                album.style.setProperty('--rot', style['--rot']);
                album.style.zIndex = style.zIndex;
            });
        }

        function extractSpineColors() {
            const albums = vinylStack.querySelectorAll('.album3D');
            albums.forEach((album) => {
                const img = album.querySelector('.albumFront img');
                const spine = album.querySelector('.albumSpine');
                if (!img || !spine) return;

                const tempImg = new Image();
                tempImg.crossOrigin = 'anonymous';
                tempImg.onload = () => {
                    const canvas = document.createElement('canvas');
                    const sampleW = 20;
                    const sampleH = tempImg.height;
                    canvas.width = sampleW;
                    canvas.height = sampleH;

                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(tempImg, 0, 0, sampleW, tempImg.height, 0, 0, sampleW, sampleH);

                    const data = ctx.getImageData(0, 0, sampleW, sampleH).data;
                    const totalPixels = sampleW * sampleH;

                    let rSum = 0, gSum = 0, bSum = 0;
                    for (let i = 0; i < data.length; i += 4) {
                        rSum += data[i];
                        gSum += data[i + 1];
                        bSum += data[i + 2];
                    }

                    const r = Math.round(rSum / totalPixels);
                    const g = Math.round(gSum / totalPixels);
                    const b = Math.round(bSum / totalPixels);
                    const lum = (r * 299 + g * 587 + b * 114) / 1000;

                    spine.style.setProperty('--spine-bg', `rgb(${r},${g},${b})`);
                    spine.style.setProperty('--spine-text', lum > 140 ? 'rgba(0,0,0,0.7)' : 'rgba(255,255,255,0.6)');
                };
                tempImg.src = img.src;
            });
        }

        vinylStack.addEventListener('scroll', () => {
            scrollLeft = vinylStack.scrollLeft;
            updateStackStyles();
        });

        document.addEventListener('DOMContentLoaded', () => {
            updateStackStyles();
            extractSpineColors();

            setTimeout(() => {
                const totalItems = vinylStack.querySelectorAll('.album3D').length;
                const spacing = 90;
                const centerScroll = (totalItems * spacing / 2) - (vinylStack.clientWidth / 2);
                vinylStack.scrollLeft = Math.max(0, centerScroll);
                scrollLeft = vinylStack.scrollLeft;
                updateStackStyles();
            }, 100);
        });

        window.addEventListener('resize', () => {
            updateStackStyles();
        });
        </script>
    </body>
</html>