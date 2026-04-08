$("#back-to-top-btn").on("click", () => {
    const closestEvent = document.getElementById('closest-event');
    if (closestEvent) {
        closestEvent.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});

$("#show-more-btn").on("click", () => {
    const oldestEvent = document.getElementById('oldest-event');
    if (oldestEvent) {
        oldestEvent.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
})