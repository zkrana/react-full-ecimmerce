<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Policy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="assets/styling/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./assets/js/main.js"></script>
</head>
<body>
    <?php include './components/header/header.php'; ?>
    <div class="w-[90%] sm:max-w-7xl mx-auto sm:pt-20 pb-14 mt-7">
        <div class="">
            <div class="mb-2">
                <h1 class="text-3xl md:text-5xl font-bold text-gray-600">Refund Policy.</h1>
            </div>
            <div class="mb-5 text-gray-400">
                <a href="<?php echo $baseUrl; ?>" class="focus:outline-none hover:underline text-gray-500">Home</a> / <span class="text-gray-600">Refund Policy</span>
            </div>
            </div>
            <div class="mt-7">
                <div class="prose prose-lg">
                    <p class="mb-4">At OurStore, we strive to provide the best possible shopping experience. If you're not
                        completely satisfied with your purchase, we're here to help.</p>
                    <h2 class="text-2xl font-semibold mb-4">Eligibility for Refunds</h2>
                    <p class="mb-4">To be eligible for a refund, please ensure that:</p>
                    <ul class="list-disc pl-8 mb-6">
                        <li>The item is in the same condition as you received it.</li>
                        <li>The item is unworn, unwashed, and undamaged.</li>
                        <li>The item is in its original packaging, with all tags attached.</li>
                        <li>You have proof of purchase (order number, receipt, or invoice).</li>
                    </ul>
                    <h2 class="text-2xl font-semibold mb-4">Exclusions</h2>
                    <p class="mb-4">Certain types of items cannot be returned, including:</p>
                    <ul class="list-disc pl-8 mb-6">
                        <li>Items marked as final sale or clearance.</li>
                        <li>Customized or personalized items.</li>
                        <li>Perishable goods such as food, flowers, or newspapers.</li>
                        <li>Gift cards or downloadable software products.</li>
                    </ul>
                    <h2 class="text-2xl font-semibold mb-4">Refund Process</h2>
                    <p class="mb-4">Once your return is received and inspected, we will send you an email to notify you
                        that we have received your returned item. We will also notify you of the approval or rejection of
                        your refund.</p>
                    <p class="mb-4">If your refund is approved, it will be processed and a credit will automatically be
                        applied to your original method of payment within a certain number of days.</p>
                    <h2 class="text-2xl font-semibold mb-4">Contact Us</h2>
                    <p>If you have any questions about our refund policy, please contact us:</p>
                    <ul class="list-disc pl-8 mb-6">
                        <li>Email: <a href="mailto:support@example.com" class="text-blue-600">support@example.com</a></li>
                        <li>Phone: 1-800-123-4567</li>
                    </ul>
                    <p class="mb-4">Our customer service team is available to assist you Monday through Friday, 9am to 5pm
                        EST.</p>
                </div>
        </div>
    </div>
</div>


<?php include './components/footer/footer.php'; ?>
<script src="./assets/js/wishlist.js"></script>

</body>
</html>