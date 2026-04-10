function initCartNotifications() {
    const cartCount = document.getElementById('count');
    const cartToast = document.getElementById('cart-toast');
    const header = document.querySelector('header');
    let toastTimer = null;

    if (!cartToast) {
        return;
    }

    function syncToastPosition() {
        const top = header ? (header.offsetHeight + 20) : 118;
        cartToast.style.top = `${top}px`;
    }

    function showCartToast(message, isError = false) {
        cartToast.textContent = message;
        cartToast.style.display = 'block';
        cartToast.style.backgroundColor = isError ? 'rgba(161, 39, 39, 0.25)' : 'rgba(64, 180, 141, 0.2)';
        cartToast.style.borderColor = isError ? 'rgba(161, 39, 39, 0.8)' : 'rgba(64, 180, 141, 0.8)';
        cartToast.style.color = isError ? '#ffd3d3' : 'rgb(182, 248, 224)';
        cartToast.style.opacity = '0';
        cartToast.style.transform = 'translateY(-6px)';
        syncToastPosition();

        window.requestAnimationFrame(() => {
            cartToast.style.opacity = '1';
            cartToast.style.transform = 'translateY(0)';
        });

        if (toastTimer !== null) {
            window.clearTimeout(toastTimer);
        }

        toastTimer = window.setTimeout(() => {
            cartToast.style.opacity = '0';
            cartToast.style.transform = 'translateY(-6px)';
            window.setTimeout(() => {
                cartToast.style.display = 'none';
            }, 200);
        }, 2200);
    }

    syncToastPosition();
    window.addEventListener('resize', syncToastPosition);

    document.addEventListener('click', async (event) => {
        const button = event.target.closest('.addCart');
        if (!button) {
            return;
        }

        event.preventDefault();

        try {
            const response = await fetch(button.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (!response.ok || data.error) {
                showCartToast(data.message || "Impossible d'ajouter l'article au panier.", true);
                return;
            }

            if (cartCount) {
                cartCount.textContent = data.count;
            }

            showCartToast(data.message || 'Article ajoute au panier.');
        } catch (error) {
            showCartToast("Impossible d'ajouter l'article au panier.", true);
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCartNotifications);
} else {
    initCartNotifications();
}
