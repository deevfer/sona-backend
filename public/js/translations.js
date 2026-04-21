const resources = {
    en: {
        translation: {
            application: "Application",
            requirements: "Requirements",
            application_text: "Rediscover your favorite music on vinyl.",
            available_title: "Available on",
            or_text: "OR",
            register_on_web: "Register on web",
            developed: 'Developed by <a href="https://www.tiktok.com/@devfer94" target="_blank">@devfer94</a>',
            terms: "Terms and conditions",
            faq: "FAQ",
            requirements_title: "Requirements",
            req_devices_title: "Compatible Devices",
            req_devices_text: "Sona is available on iPhone, iPad, Android devices, tablets, and computers via a web browser.",

            req_os_title: "Compatible operating systems",
            req_os_text: "iPhone: iOS 18 or later<br>iPad: iPadOS 18 or later<br>Android (smartphones and tablets): Android 10 or later<br>Web/Desktop: Up-to-date browser (Chrome, Safari, or Edge recommended)",

            req_music_title: "Required music service",
            req_music_text: "To use Sona, you need an active subscription to a compatible service:<br>Apple Music (currently available) - iOS<br>Spotify Premium (coming soon) - iOS<br>Any music streaming app - Android",

            req_account_title: "Account and access",
            req_account_text: "You must create a Sona account, sign in on your device, and link your compatible music service.",

            req_internet_title: "Internet connection",
            req_internet_text: "An internet connection is required to sign in, sync your library, and play music from your connected service.",

            req_permissions_title: "Permissions",
            req_permissions_text: "You must authorize Sona to connect to your music service and allow audio playback on the device.",

            req_access_title: "Access to Sona",
            req_access_text: "Sona operates on a one-time payment basis, granting lifetime access to the full experience.",

            req_notes_title: "Important Notes",
            req_notes_text: "Sona does not store music; it plays content from your streaming service.<br>Some features may depend on your active music provider.<br>Spotify will be available soon.<br>The experience may vary slightly depending on your device, browser, or operating system version.",
            terms_title: "Terms and conditions",
            terms_updated: "Last updated: March 2026",
            terms_intro: "By accessing and using Sona, you agree to the following terms and conditions. If you do not agree with any of them, we recommend that you do not use the service.",

            terms_service_title: "Service Description",
            terms_service_text: "Sona is a digital app that lets users view and play their music from compatible streaming services such as Apple Music and, coming soon, Spotify (iOS) and any music streaming platform (Android), through a visual experience inspired by vinyl records.<br>Sona does not store or distribute its own music, but rather acts as an interface that connects to third-party services.",

            terms_requirements_title: "Requirements for Use",
            terms_requirements_text: "To use Sona, the user must have an active Sona account, a valid subscription to Apple Music or, in the future, Spotify Premium (iOS), any music streaming app installed on the device (Android), a compatible device, and an internet connection.",

            terms_account_title: "User Account",
            terms_account_text: "The user is responsible for maintaining the confidentiality of their account, providing accurate information, and for all activities carried out from their account.<br>Account use is personal, and only one device may be logged in at a time.<br>Sona is not liable for unauthorized access resulting from the misuse of credentials.",

            terms_integration_title: "Integration with Third-Party Services",
            terms_integration_text: "Sona integrates with external services such as Apple Music and Spotify on iOS and any music streaming platform on Android.<br>By using these integrations, you also agree to the terms and policies of those services.",

            terms_payment_title: "Payment and Access",
            terms_payment_text: "Sona offers access for a one-time payment of $2.99 if the user signs up via the website or Android app, and $3.99 when purchasing on iPhone or iPad (lifetime access).<br>Payment grants access to currently available features and future updates, unless otherwise specified.<br>Access is personal and non-transferable.<br>Resale or distribution of accounts is not permitted.",

            terms_refund_title: "Refund Policy",
            terms_refund_text: "Due to the digital nature of the service, payments made are final and non-refundable, except in cases required by applicable law.",

            terms_use_title: "Acceptable Use",
            terms_use_text: "The user agrees not to use Sona for illegal purposes, attempt to compromise the platform’s security, interfere with the service’s operation, or reverse engineer or copy the system.",

            terms_ip_title: "Intellectual Property",
            terms_ip_text: "All rights related to Sona, including design, interface, code, and brand, are the property of its creators.<br>The musical content belongs to its respective owners (Apple Music, Spotify, and others).",

            terms_availability_title: "Service Availability",
            terms_availability_text: "Sona is provided “as is” and “as available.”<br>There is no guarantee that the service will be continuous or error-free.<br>Sona reserves the right to modify, suspend, or discontinue the service at any time.",

            terms_liability_title: "Limitation of Liability",
            terms_liability_text: "Sona shall not be liable for data loss, service interruptions, issues arising from third-party services, or indirect or incidental damages.",

            terms_changes_title: "Changes",
            terms_changes_text: "Sona reserves the right to update these terms at any time.<br>Your continued use of the service constitutes your acceptance of the changes.",

            terms_contact_title: "Contact",
            terms_contact_text: "For any questions regarding these terms, please contact us at: sona@fernandovasquez.tech",
            faq_title:"FAQ",
            faq_q1: "What is Sona?",
            faq_a1: "Sona is an app that lets you rediscover your music through a visual experience inspired by vinyl records, connecting to services like Apple Music and, coming soon, Spotify (iOS) and any music streaming app (Android).",

            faq_q2: "Is Sona a music service?",
            faq_a2: "No. Sona does not store or distribute music. It acts as an interface that connects to your streaming service to play your library.",

            faq_q3: "What do I need to use Sona?",
            faq_a3: "You need a compatible device, an internet connection, a Sona account, and an active subscription to Apple Music or, coming soon, Spotify Premium (iOS) and any music platform (Android).",

            faq_q4: "Does Sona work without a subscription?",
            faq_a4: "No. Sona requires an active subscription to a compatible streaming service to play music.",

            faq_q5: "Is Spotify available yet?",
            faq_a5: "Sona is currently compatible with Apple Music. Spotify Premium integration is coming soon (iOS) and any music streaming app (Android).",

            faq_q6: "On which devices can I use Sona?",
            faq_a6: "Sona is available on iPhone, iPad, Android devices, tablets, and computers via modern browsers.",

            faq_q7: "How does payment work?",
            faq_a7: "Sona operates on a one-time payment basis, giving you lifetime access to the app.",

            faq_q8: "Is it a subscription?",
            faq_a8: "No. The payment is a one-time fee; there are no recurring charges.",

            faq_q9: "Does it include future updates?",
            faq_a9: "Yes. Your payment includes access to future improvements at no additional cost.",

            faq_q10: "Can I use Sona on multiple devices?",
            faq_a10: "Yes, you can access your account from different compatible devices.",

            faq_q11: "Does Sona store my music?",
            faq_a11: "No. All music is streamed directly from your connected service.",

            faq_q12: "Does Sona work offline?",
            faq_a12: "No. An internet connection is required to play music.",

            faq_q13: "Can I get a refund?",
            faq_a13: "Payments are non-refundable except where required by law.",

            faq_q14: "Will Sona keep improving?",
            faq_a14: "Yes. Sona is constantly evolving to improve the experience.",

            faq_q15: "Where can I get support?",
            faq_a15: "Contact us at: sona@fernandovasquez.tech",
        }
    },
    es: {
        translation: {
            application: "Aplicación",
            requirements: "Requerimientos",
            application_text: "Redescubre tu música favorita en vinyl.",
            available_title: "Disponible para",
            or_text: "O",
            register_on_web: "Regístrate en la web",
            developed: 'Desarrollado por <a href="https://www.tiktok.com/@devfer94" target="_blank">@devfer94</a>',
            terms: "Términos y condiciones",
            faq: "Preguntas frecuentes",
            requirements_title: "Requerimientos",
            req_devices_title: "Dispositivos compatibles",
            req_devices_text: "Sona está disponible en iPhone, iPad, dispositivos Android, tablets y computadoras a través de un navegador web.",
            
            req_os_title: "Sistemas operativos compatibles",
            req_os_text: "iPhone: iOS 18 o superior<br>iPad: iPadOS 18 o superior<br>Android (smartphones y tablets): Android 10 o superior<br>Web/Escritorio: Navegador actualizado (Chrome, Safari o Edge recomendado)",
            
            req_music_title: "Servicio de música requerido",
            req_music_text: "Para usar Sona, necesitas una suscripción activa a un servicio compatible:<br>Apple Music (actualmente disponible) -iOS<br>Spotify Premium (próximamente) -iOS<br>Cualquier aplicación de streaming de música - Android",
            
            req_account_title: "Cuenta y acceso",
            req_account_text: "Debes crear una cuenta en Sona, iniciar sesión en tu dispositivo y vincular tu servicio de música compatible.",
            
            req_internet_title: "Conexión a internet",
            req_internet_text: "Se requiere conexión a internet para iniciar sesión, sincronizar tu biblioteca y reproducir música desde tu servicio conectado.",
            
            req_permissions_title: "Permisos",
            req_permissions_text: "Debes autorizar a Sona para conectarse a tu servicio de música y permitir la reproducción de audio en el dispositivo.",
            
            req_access_title: "Acceso a Sona",
            req_access_text: "Sona funciona con un pago único, otorgando acceso de por vida a toda la experiencia.",
            
            req_notes_title: "Notas importantes",
            req_notes_text: "Sona no almacena música; reproduce contenido desde tu servicio de streaming.<br>Algunas funciones pueden depender de tu proveedor de música activo.<br>Spotify estará disponible pronto.<br>La experiencia puede variar ligeramente según tu dispositivo, navegador o versión del sistema operativo.",
            terms_title: "Términos y condiciones",
            terms_updated: "Última actualización: Marzo 2026",
            terms_intro: "Al acceder y utilizar Sona, aceptas los siguientes términos y condiciones. Si no estás de acuerdo con alguno de ellos, te recomendamos no utilizar el servicio.",

            terms_service_title: "Descripción del servicio",
            terms_service_text: "Sona es una aplicación digital que permite a los usuarios visualizar y reproducir su música desde servicios de streaming compatibles como Apple Music y, próximamente, Spotify (iOS) y cualquier plataforma de streaming de música (Android), a través de una experiencia visual inspirada en discos de vinilo.<br>Sona no almacena ni distribuye música propia, sino que actúa como una interfaz que se conecta a servicios de terceros.",

            terms_requirements_title: "Requisitos de uso",
            terms_requirements_text: "Para utilizar Sona, el usuario debe contar con una cuenta activa en Sona, una suscripción válida a Apple Music o, en el futuro, Spotify Premium (iOS), cualquier plataforma de streaming de música instalada en el dispositivo (Android), un dispositivo compatible y conexión a internet.",

            terms_account_title: "Cuenta de usuario",
            terms_account_text: "El usuario es responsable de mantener la confidencialidad de su cuenta, proporcionar información veraz y de todas las actividades realizadas desde su cuenta.<br>El uso de la cuenta es personal y solo puede iniciarse sesión en un dispositivo a la vez.<br>Sona no se hace responsable por accesos no autorizados derivados del mal uso de las credenciales.",

            terms_integration_title: "Integración con servicios de terceros",
            terms_integration_text: "Sona se integra con servicios externos como Apple Music y Spotify en iOS y cualquier plataforma de streaming de música en Android.<br>Al utilizar estas integraciones, también aceptas los términos y políticas de dichos servicios.",

            terms_payment_title: "Pago y acceso",
            terms_payment_text: "Sona ofrece acceso mediante un pago único de $2.99 si el usuario se registra desde la web o Android, y $3.99 al adquirirlo en iPhone o iPad (acceso de por vida).<br>El pago otorga acceso a las funcionalidades actuales y futuras actualizaciones, salvo que se indique lo contrario.<br>El acceso es personal e intransferible.<br>No está permitida la reventa o distribución de cuentas.",

            terms_refund_title: "Política de reembolso",
            terms_refund_text: "Debido a la naturaleza digital del servicio, los pagos realizados son finales y no reembolsables, salvo en los casos requeridos por la ley aplicable.",

            terms_use_title: "Uso aceptable",
            terms_use_text: "El usuario se compromete a no utilizar Sona con fines ilegales, intentar vulnerar la seguridad de la plataforma, interferir con el funcionamiento del servicio o realizar ingeniería inversa o copia del sistema.",

            terms_ip_title: "Propiedad intelectual",
            terms_ip_text: "Todos los derechos relacionados con Sona, incluyendo diseño, interfaz, código y marca, son propiedad de sus creadores.<br>El contenido musical pertenece a sus respectivos propietarios (Apple Music, Spotify y otros).",

            terms_availability_title: "Disponibilidad del servicio",
            terms_availability_text: "Sona se proporciona \"tal cual\" y \"según disponibilidad\".<br>No se garantiza que el servicio sea continuo o libre de errores.<br>Sona se reserva el derecho de modificar, suspender o interrumpir el servicio en cualquier momento.",

            terms_liability_title: "Limitación de responsabilidad",
            terms_liability_text: "Sona no será responsable por pérdida de datos, interrupciones del servicio, problemas derivados de servicios de terceros o daños indirectos o incidentales.",

            terms_changes_title: "Cambios",
            terms_changes_text: "Sona se reserva el derecho de actualizar estos términos en cualquier momento.<br>El uso continuo del servicio constituye tu aceptación de dichos cambios.",

            terms_contact_title: "Contacto",
            terms_contact_text: "Para cualquier consulta sobre estos términos, puedes contactarnos en: sona@fernandovasquez.tech",
            faq_title: "Preguntas Frecuentes",
            faq_q1: "¿Qué es Sona?",
            faq_a1: "Sona es una app que te permite redescubrir tu música a través de una experiencia visual inspirada en discos de vinilo, conectándose a servicios como Apple Music y, próximamente, Spotify (iOS) y cualquier aplicación de música (Android).",
            
            faq_q2: "¿Sona es un servicio de música?",
            faq_a2: "No. Sona no almacena ni distribuye música. Actúa como una interfaz que se conecta a tu servicio de streaming para reproducir tu biblioteca.",
            
            faq_q3: "¿Qué necesito para usar Sona?",
            faq_a3: "Necesitas un dispositivo compatible, una conexión a internet, una cuenta de Sona y una suscripción activa a Apple Music o, próximamente, Spotify Premium (iOS) y cualquier plataforma de música (Android).",
            
            faq_q4: "¿Sona funciona sin una suscripción a Apple Music o Spotify?",
            faq_a4: "No. Sona requiere una suscripción activa a un servicio de streaming compatible para reproducir música.",
            
            faq_q5: "¿Spotify ya está disponible?",
            faq_a5: "Actualmente, Sona es compatible con Apple Music. La integración con Spotify Premium estará disponible próximamente (iOS) y cualquier plataforma de música (Android)..",
            
            faq_q6: "¿En qué dispositivos puedo usar Sona?",
            faq_a6: "Sona está disponible en iPhone, iPad, dispositivos Android, tablets y computadoras a través de navegadores modernos.",
            
            faq_q7: "¿Necesito descargar una app?",
            faq_a7: "Puedes usar Sona desde la web o a través de sus aplicaciones nativas en dispositivos compatibles.",
            
            faq_q8: "¿Cómo funciona el pago?",
            faq_a8: "Sona funciona con un pago único que te da acceso de por vida a la app.",
            
            faq_q9: "¿El pago es una suscripción mensual?",
            faq_a9: "No. El pago es único; no hay cargos recurrentes.",
            
            faq_q10: "¿El pago incluye mejoras futuras?",
            faq_a10: "Sí. El pago único incluye acceso a futuras mejoras y actualizaciones de Sona sin costo adicional.",
            
            faq_q11: "¿Cada cuánto se actualiza Sona?",
            faq_a11: "Las mejoras y actualizaciones se implementan de forma gradual y esporádica, con el objetivo de mejorar continuamente la experiencia sin comprometer la estabilidad del servicio.",
            
            faq_q12: "¿Puedo usar Sona en varios dispositivos?",
            faq_a12: "Sí, puedes acceder a tu cuenta desde varios dispositivos compatibles iniciando sesión con tu cuenta.",
            
            faq_q13: "¿Puedo compartir mi cuenta con otras personas?",
            faq_a13: "No. El acceso es personal e intransferible.",
            
            faq_q14: "¿Sona almacena mi música?",
            faq_a14: "No. Toda la música se reproduce directamente desde Apple Music o Spotify.",
            
            faq_q15: "¿Sona tiene acceso a mi cuenta de música?",
            faq_a15: "Sona solo solicita los permisos necesarios para reproducir y mostrar tu música. No almacena tu información personal ni tus credenciales.",
            
            faq_q16: "¿Qué pasa si mi música no aparece?",
            faq_a16: "Esto puede depender del servicio de streaming o de la sincronización. Asegúrate de tener una conexión activa y que tu cuenta esté correctamente vinculada.",
            
            faq_q17: "¿Sona funciona sin conexión a internet?",
            faq_a17: "No. Se requiere conexión a internet para sincronizar y reproducir música desde tu servicio de streaming.",
            
            faq_q18: "¿Puedo cancelar mi acceso a Sona?",
            faq_a18: "Sí, puedes dejar de usar Sona en cualquier momento, pero el pago es único y no requiere cancelación.",
            
            faq_q19: "¿Sona ofrece reembolsos?",
            faq_a19: "Los pagos no son reembolsables, excepto en los casos requeridos por la ley aplicable.",
            
            faq_q20: "¿La experiencia es igual en todos los dispositivos?",
            faq_a20: "Puede variar ligeramente dependiendo del dispositivo, sistema operativo y navegador.",
            
            faq_q21: "¿Sona seguirá mejorando?",
            faq_a21: "Sí. Sona está en constante evolución y seguirá incorporando mejoras para ofrecer una mejor experiencia.",
            
            faq_q22: "¿Dónde puedo obtener ayuda o soporte?",
            faq_a22: "Puedes contactarnos en: sona@fernandovasquez.tech",
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {
    i18next.init({
        lng: localStorage.getItem('site_lang') || 'en',
        resources,
        interpolation: {
            escapeValue: false
        }
    }, function () {
        updateContent();
        updateActiveLang(i18next.language);
        console.log(i18next.t('requirements_title'));
    });
});

function updateContent() {
    document.querySelectorAll('[data-i18n]').forEach(element => {
        const key = element.getAttribute('data-i18n');
        element.innerHTML = i18next.t(key);
    });
}

function changeLanguage(lang) {
    i18next.changeLanguage(lang, () => {
        localStorage.setItem('site_lang', lang);
        updateContent();
        updateActiveLang(lang);
    });
}

function updateActiveLang(currentLang) {
    const buttons = document.querySelectorAll('.langBtn');

    buttons.forEach(btn => {
        btn.classList.remove('active');

        if (btn.getAttribute('data-lang') === currentLang) {
            btn.classList.add('active');
        }
    });
}