export const urlMixin = {
    methods: {
        getClientShowUrl(clientId) {
            const clientShowUrl = document.querySelector('meta[name="web-clients-show-url"]').getAttribute('content')
            return `${clientShowUrl}/${clientId}`;
        },
        getClientCreateUrl() {
            return document.querySelector('meta[name="web-clients-create-url"]').getAttribute('content')
        },
        getClientsIndexUrl() {
            return document.querySelector('meta[name="web-clients-index-url"]').getAttribute('content')
        }
    }
};
