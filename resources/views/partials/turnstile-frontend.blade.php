<div
    x-data="{
        widgetId: null,
        getTheme() {
            return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        },
        renderWidget() {
            if (!window.turnstile) return;
            if (this.widgetId !== null) {
                try { window.turnstile.remove(this.widgetId); } catch (e) {}
            }
            const input = this.$el.closest('form').querySelector('input[name=turnstile_token]');
            this.widgetId = window.turnstile.render(this.$refs.widget, {
                sitekey: this.$el.dataset.sitekey,
                theme: this.getTheme(),
                callback:           (token) => { if (input) input.value = token; },
                'expired-callback': ()       => { if (input) input.value = ''; },
                'error-callback':   ()       => { if (input) input.value = ''; },
            });
        },
        init() {
            const tryRender = () => {
                if (window.turnstile) {
                    this.renderWidget();
                    const observer = new MutationObserver(() => this.renderWidget());
                    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
                } else {
                    setTimeout(tryRender, 100);
                }
            };
            tryRender();
        }
    }"
    data-sitekey="{{ config('services.turnstile.site_key') }}"
    style="display:flex;justify-content:center;width:100%;padding:0.5rem 0;">
    <div x-ref="widget"></div>
</div>
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit" async defer></script>
