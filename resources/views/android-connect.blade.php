<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connecting Apple Music...</title>
    <script src="https://js-cdn.music.apple.com/musickit/v3/musickit.js"></script>
    <style>
        body {
            margin: 0;
            padding: 24px;
            font-family: Arial, sans-serif;
            background: #000;
            color: #fff;
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .card {
            max-width: 420px;
        }

        h1 {
            font-size: 22px;
            margin-bottom: 12px;
        }

        p {
            opacity: 0.8;
            line-height: 1.5;
        }

        .success {
            color: #7CFF8A;
        }

        .error {
            color: #FF7C7C;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Connecting Apple Music...</h1>
        <p id="status">Please complete the authorization window.</p>
    </div>

    <script>
        const API_BASE = @json($apiBase);
        const APP_TOKEN = @json($token);
        const APPLE_DEV_NAME = "Sona";

        function setStatus(message, className = "") {
            const el = document.getElementById("status");
            el.className = className;
            el.innerHTML = message;
        }

        function waitForMusicKit() {
            return new Promise((resolve, reject) => {
                if (window.MusicKit && typeof window.MusicKit.configure === "function") {
                    resolve(window.MusicKit);
                    return;
                }

                const timeout = setTimeout(() => {
                    reject(new Error("MusicKit JS did not finish loading."));
                }, 8000);

                const handleLoaded = () => {
                    clearTimeout(timeout);

                    if (window.MusicKit && typeof window.MusicKit.configure === "function") {
                        resolve(window.MusicKit);
                    } else {
                        reject(new Error("MusicKit is not available."));
                    }
                };

                window.addEventListener("musickitloaded", handleLoaded, { once: true });
            });
        }

        async function getDeveloperToken() {
            const res = await fetch(`${API_BASE}/api/apple-music/token`, {
                method: "GET",
                headers: {
                    Authorization: `Bearer ${APP_TOKEN}`,
                    Accept: "application/json",
                },
            });

            if (!res.ok) {
                const text = await res.text();
                throw new Error(text || "Could not get developer token.");
            }

            const data = await res.json();

            if (!data.token) {
                throw new Error("Developer token missing.");
            }

            return data.token;
        }

        async function connectAppleMusic() {
            try {
                const developerToken = await getDeveloperToken();
                const MusicKit = await waitForMusicKit();

                let music = null;

                try {
                    music = MusicKit.getInstance();
                } catch {
                    music = null;
                }

                if (!music) {
                    MusicKit.configure({
                        developerToken,
                        app: {
                            name: APPLE_DEV_NAME,
                            build: "1.0.0",
                        },
                    });

                    await new Promise((resolve) => setTimeout(resolve, 350));

                    music = MusicKit.getInstance();
                }

                if (!music) {
                    throw new Error("Apple Music could not be initialized.");
                }

                const musicUserToken = await music.authorize();

                if (!musicUserToken) {
                    throw new Error("No Music User Token received.");
                }

                const connectRes = await fetch(`${API_BASE}/api/apple-music/connect`, {
                    method: "POST",
                    headers: {
                        Authorization: `Bearer ${APP_TOKEN}`,
                        Accept: "application/json",
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        music_user_token: musicUserToken,
                        provider_user_id: null,
                        scopes: ["musickit_web"],
                    }),
                });

                if (!connectRes.ok) {
                    const text = await connectRes.text();
                    throw new Error(text || "Could not save Apple Music connection.");
                }

                setStatus("Apple Music connected successfully. Returning to app...", "success");

                setTimeout(() => {
                    window.location.href = "sona://connected";
                }, 800);

            } catch (error) {
                console.error(error);
                setStatus(error.message || "An unexpected error occurred.", "error");
            }
        }

        connectAppleMusic();
    </script>
</body>
</html>