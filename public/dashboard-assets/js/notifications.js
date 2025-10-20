// ---- Minimal Vanilla JS Reverb/Pusher + Sound Notification ----

// window.AGENT_ID და window.REVERB_APP_KEY უნდა გააჩნდეთ Blade-ში

if (!window.AGENT_ID || !window.REVERB_APP_KEY) {
    console.warn('Agent ID or Reverb key not set.');
} else {
    // Load Pusher (standalone, from public/js)
    // გადმოწერე pusher.min.js და დადე public/js/pusher.min.js
    // შემდეგ include Blade-ში <script src="{{ asset("js/pusher.min.js") }}"></script>
    // Simple Echo implementation
    class SimpleEcho {
        constructor(options) {
            this.pusher = new Pusher(options.key, {
                cluster: options.cluster || 'mt1',
                forceTLS: options.forceTLS || true
            });
        }

        private(channel) {
            return this.pusher.subscribe(channel);
        }
    }

    window.Echo = new SimpleEcho({
        key: window.REVERB_APP_KEY,
        cluster: 'mt1',
        forceTLS: true
    });

    // Listen to private agent channel
    let channel = window.Echo.private('agent.' + window.AGENT_ID);
    channel.bind('AgentFormSaved', function(data) {
        console.log('New form saved:', data.formData);

        // Visual notification
        alert('New form submitted!');

        // Sound notification
        let audio = new Audio('{{ asset("dashboard-assets/notification.mp3") }}');
        audio.play();
    });
}
