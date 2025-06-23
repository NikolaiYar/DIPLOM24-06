document.addEventListener("DOMContentLoaded", function () {
    const stars = document.querySelectorAll('.star');
    const ratingContainer = document.querySelector('.star-rating');
    const recipeId = ratingContainer.getAttribute('data-recipe-id');
    const userRating = parseInt(ratingContainer.getAttribute('data-user-rating'));

    // Подсвечивает звезды до указанного рейтинга
    function highlightStars(rating) {
        stars.forEach(star => {
            const value = parseInt(star.getAttribute('data-value'));
            star.classList.toggle('active', value <= rating);
        });
    }

    // Показываем сохраненную оценку пользователя
    if (userRating) {
        highlightStars(userRating);
    }

    stars.forEach(star => {
        star.addEventListener('click', function () {
            const rating = this.getAttribute('data-value');

            fetch('/recipes/rate-recipe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    rating: rating,
                    recipe_id: recipeId
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Ошибка HTTP");
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Оценка сохранена:', data);
                    highlightStars(rating);
                })
                .catch(error => {
                    console.error('Ошибка при отправке:', error);
                });
        });
    });
});
