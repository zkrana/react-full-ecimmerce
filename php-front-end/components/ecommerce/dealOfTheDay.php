<div class=" mt-14">
    <h4 class="sm:text-2xl text-lg font-semibold pb-3 border-b border-slate-200 capitalize">
        Deal of The Day
    </h4>
    <div class="bg-gray-100 mt-9 w-full rounded-lg overflow-hidden relative shadow-md">
    <img src="deal1.jpg" alt="Deal 1" class="w-full h-64 object-cover">
    <div class="p-6">
        <h3 class="text-xl font-semibold mb-2 text-gray-800">Product Name</h3>
        <p class="text-gray-600 mb-4">Description of the product goes here. This is a very long description to showcase the layout.</p>
        <div class="flex items-center justify-between mb-4">
            <p class="text-lg text-green-600 font-semibold">$19.99</p>
            <p class="text-sm text-gray-400 line-through">$29.99</p>
        </div>
        <button type="button" class="add-to-cart-btn inline-flex justify-center items-center text-sm bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 transition duration-300 ease-in-out" data-product-id="6">
            <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 21">
                <path d="M15 12a1 1 0 0 0 .962-.726l2-7A1 1 0 0 0 17 3H3.77L3.175.745A1 1 0 0 0 2.208 0H1a1 1 0 0 0 0 2h.438l.6 2.255v.019l2 7 .746 2.986A3 3 0 1 0 9 17a2.966 2.966 0 0 0-.184-1h2.368c-.118.32-.18.659-.184 1a3 3 0 1 0 3-3H6.78l-.5-2H15Z"></path>
            </svg>
            Add to Cart
        </button>
    </div>
    <div class="absolute top-0 left-0 bg-green-500 text-white text-xs font-semibold px-4 py-2 rounded-tr-lg rounded-bl-lg uppercase">Deal of the Day</div>
    <div class="timer absolute top-2 right-2 bg-gray-800 text-white text-xs font-semibold px-3 py-1 rounded-lg" id="dealTimer1"></div>
</div>



</div>
<script>
// Countdown Timer Function
function countdown(endTime, elementId) {
    const timerElement = document.getElementById(elementId);
    
    function updateTimer() {
        const currentTime = new Date().getTime();
        const distance = endTime - currentTime;
        
        // Calculating time units
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        // Update the timer display
        timerElement.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
        
        // Check if the timer has reached zero
        if (distance < 0) {
            clearInterval(timerInterval);
            timerElement.innerHTML = "Deal Expired";
        }
    }

    updateTimer();
    const timerInterval = setInterval(updateTimer, 1000); // Update timer every second
}

// Set the end time for the deal (replace with your desired end time)
const endTime = new Date().getTime() + 1000 * 60 * 60 * 24 * 3; // Example: 3 days from now

// Call countdown function for each deal
countdown(endTime, "dealTimer1");

</script>