<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }} | HOME</title>

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
                    <h1 data-i18n="terms_title">Terms and conditions</h1>
                    <div class="terms">

                        <p data-i18n="terms_updated">
                            Last updated: March 2026
                        </p>

                        <p data-i18n="terms_intro">
                            By accessing and using Sona, you agree to the following terms and conditions. If you do not agree with any of them, we recommend that you do not use the service.
                        </p>

                        <h3 data-i18n="terms_service_title">Service Description</h3>
                        <p data-i18n="terms_service_text">
                            Sona is a digital application that allows users to view and play their music from compatible streaming services, such as Apple Music and, coming soon, Spotify, through a visual experience inspired by vinyl records.<br>
                            Sona does not store or distribute its own music, but rather acts as an interface that connects to third-party services.
                        </p>

                        <h3 data-i18n="terms_requirements_title">Requirements for Use</h3>
                        <p data-i18n="terms_requirements_text">
                            To use Sona, the user must have an active Sona account, a valid subscription to Apple Music or, in the future, Spotify Premium, a compatible device, and an internet connection.
                        </p>

                        <h3 data-i18n="terms_account_title">User Account</h3>
                        <p data-i18n="terms_account_text">
                            The user is responsible for maintaining the confidentiality of their account, providing accurate information, and for all activities carried out from their account.<br>
                            Account use is personal, and only one device may be logged in at a time.<br>
                            Sona is not liable for unauthorized access resulting from the misuse of credentials.
                        </p>

                        <h3 data-i18n="terms_integration_title">Integration with Third-Party Services</h3>
                        <p data-i18n="terms_integration_text">
                            Sona integrates with external services such as Apple Music and Spotify.<br>
                            By using these integrations, you also agree to the terms and policies of those services.
                        </p>

                        <h3 data-i18n="terms_payment_title">Payment and Access</h3>
                        <p data-i18n="terms_payment_text">
                            Sona offers access for a one-time payment of $2.99 if the user signs up via the website or Android app, and $3.99 when purchasing on iPhone or iPad (lifetime access).<br>
                            Payment grants access to currently available features and future updates, unless otherwise specified.<br>
                            Access is personal and non-transferable.<br>
                            Resale or distribution of accounts is not permitted.
                        </p>

                        <h3 data-i18n="terms_refund_title">Refund Policy</h3>
                        <p data-i18n="terms_refund_text">
                            Due to the digital nature of the service, payments made are final and non-refundable, except in cases required by applicable law.
                        </p>

                        <h3 data-i18n="terms_use_title">Acceptable Use</h3>
                        <p data-i18n="terms_use_text">
                            The user agrees not to use Sona for illegal purposes, attempt to compromise the platform’s security, interfere with the service’s operation, or reverse engineer or copy the system.
                        </p>

                        <h3 data-i18n="terms_ip_title">Intellectual Property</h3>
                        <p data-i18n="terms_ip_text">
                            All rights related to Sona, including design, interface, code, and brand, are the property of its creators.<br>
                            The musical content belongs to its respective owners (Apple Music, Spotify, and others).
                        </p>

                        <h3 data-i18n="terms_availability_title">Service Availability</h3>
                        <p data-i18n="terms_availability_text">
                            Sona is provided “as is” and “as available.”<br>
                            There is no guarantee that the service will be continuous or error-free.<br>
                            Sona reserves the right to modify, suspend, or discontinue the service at any time.
                        </p>

                        <h3 data-i18n="terms_liability_title">Limitation of Liability</h3>
                        <p data-i18n="terms_liability_text">
                            Sona shall not be liable for data loss, service interruptions, issues arising from third-party services, or indirect or incidental damages.
                        </p>

                        <h3 data-i18n="terms_changes_title">Changes</h3>
                        <p data-i18n="terms_changes_text">
                            Sona reserves the right to update these terms at any time.<br>
                            Your continued use of the service constitutes your acceptance of the changes.
                        </p>

                        <h3 data-i18n="terms_contact_title">Contact</h3>
                        <p data-i18n="terms_contact_text">
                            For any questions regarding these terms, please contact us at: sona@fernandovasquez.tech
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
    </body>
</html>