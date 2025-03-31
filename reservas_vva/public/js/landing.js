document.addEventListener("DOMContentLoaded", function() {
    const sliderContainer = document.querySelector('.slider-container');
        const sliderItems = document.querySelectorAll('.slider-item');
        let sliderWidth = sliderItems[0].clientWidth;
        window.addEventListener('resize', () => {
            sliderWidth = sliderItems[0].clientWidth;
        });
        let currentIndex = 0;

        console.log(sliderItems[0].clientWidth);
        // Obtener elementos de las flechas
        const arrowLeft = document.querySelector('.slider-arrow-left');
        const arrowRight = document.querySelector('.slider-arrow-right');

        // Agregar eventos a las flechas
        arrowLeft.addEventListener('click', showPreviousSlide);
        arrowRight.addEventListener('click', showNextSlide);

        // Función para mostrar el slide anterior
        function showPreviousSlide() {
            if (currentIndex > 0) {
                currentIndex--;
                sliderContainer.style.transform = `translateX(-${currentIndex * sliderWidth}px)`;
            }
        }

        // Función para mostrar el siguiente slide
        function showNextSlide() {
            let numSlide = window.innerWidth < 600 ? 1 : (window.innerWidth < 1500 && window.innerWidth > 1000 ? 3 : (window.innerWidth <= 1000 ? 2 : 4));

            if (currentIndex < sliderItems.length - numSlide) {
                currentIndex++;
                sliderContainer.style.transform = `translateX(-${currentIndex * sliderWidth}px)`;
            }
        }
});
