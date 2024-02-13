<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="assets/styling/style.css">
    <script src="./assets/js/main.js"></script>
</head>
<body>
    <?php include './components/header/header.php'; ?>
    <div class="container">
        <div class="min-h-screen w-full sm:max-w-7xl mx-auto pt-20 pb-14 mt-7">
        <div class="px-5">
            <div class="mb-2">
                <h1 class="text-3xl md:text-5xl font-bold text-gray-600">Checkout.</h1>
            </div>
            <div class="mb-5 text-gray-400">
                <a href="index.php" class="focus:outline-none hover:underline text-gray-500">Home</a> / <a href="cart.php" class="focus:outline-none hover:underline text-gray-600">Cart</a> / <span class="text-gray-600">Checkout</span>
            </div>
        </div>
        <div class="w-full bg-white border-t border-b border-gray-200 px-5 py-10 text-gray-800">
            <div class="w-full">
                <div class="-mx-3 md:flex items-start">
                    <div class="px-3 md:w-7/12 lg:pr-10">
                        <div class="w-full mx-auto text-gray-800 font-light mb-6 border-b border-gray-200 pb-6">
                            <div class="w-full flex items-center">
                                <div class="overflow-hidden rounded-lg w-16 h-16 bg-gray-50 border border-gray-200">
                                    <img class=" w-16 h-16 object-contain" src="https://images.unsplash.com/photo-1572635196237-14b3f281503f?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1160&q=80" alt="">
                                </div>
                                <div class="flex-grow pl-3">
                                    <h6 class="font-semibold uppercase text-gray-600">Ray Ban Sunglasses.</h6>
                                    <p class="text-gray-400">x 1</p>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-600 text-xl">$210</span><span class="font-semibold text-gray-600 text-sm">.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <div class="-mx-2 flex items-end justify-end">
                                <div class="flex-grow px-2 lg:max-w-xs">
                                    <label class="text-gray-600 font-semibold text-sm mb-2 ml-1">Discount code</label>
                                    <div>
                                        <input class="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors" placeholder="XXXXXX" type="text"/>
                                    </div>
                                </div>
                                <div class="px-2">
                                    <button class="block w-full max-w-xs mx-auto border border-transparent bg-gray-400 hover:bg-gray-500 focus:bg-gray-500 text-white rounded-md px-5 py-2 font-semibold">APPLY</button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6 pb-6 border-b border-gray-200 text-gray-800">
                            <div class="w-full flex mb-3 items-center">
                                <div class="flex-grow">
                                    <span class="text-gray-600">Subtotal</span>
                                </div>
                                <div class="pl-3">
                                    <span class="font-semibold">$190.91</span>
                                </div>
                            </div>
                            <div class="w-full flex items-center">
                                <div class="flex-grow">
                                    <span class="text-gray-600">Taxes (GST)</span>
                                </div>
                                <div class="pl-3">
                                    <span class="font-semibold">$19.09</span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6 pb-6 border-b border-gray-200 md:border-none text-gray-800 text-xl">
                            <div class="w-full flex items-center">
                                <div class="flex-grow">
                                    <span class="text-gray-600">Total</span>
                                </div>
                                <div class="pl-3">
                                    <span class="font-semibold text-gray-400 text-sm">AUD</span> <span class="font-semibold">$210.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-3 md:w-5/12">
                        <div class="w-full mx-auto rounded-lg bg-white border border-gray-200 p-3 text-gray-800 font-light mb-6">
                            <h2 class="text-2xl font-semibold mb-6">Shipping Details</h2>

                                <form action="process_form.php" method="post">

                                    <!-- First Name -->
                                    <div class="mb-4">
                                        <label for="first_name" class="block text-sm font-medium text-gray-600">First Name</label>
                                        <input type="text" id="first_name" name="first_name" class="mt-1 p-2 w-full border rounded-md">
                                    </div>

                                    <!-- Last Name -->
                                    <div class="mb-4">
                                        <label for="last_name" class="block text-sm font-medium text-gray-600">Last Name</label>
                                        <input type="text" id="last_name" name="last_name" class="mt-1 p-2 w-full border rounded-md">
                                    </div>

                                    <!-- Billing Address -->
                                    <div class="mb-4">
                                        <label for="billing_address" class="block text-sm font-medium text-gray-600">Billing Address</label>
                                        <textarea id="billing_address" name="billing_address" class="mt-1 p-2 w-full border rounded-md"></textarea>
                                    </div>

                                    <!-- City -->
                                    <div class="mb-4">
                                        <label for="city" class="block text-sm font-medium text-gray-600">City</label>
                                        <input type="text" id="city" name="city" class="mt-1 p-2 w-full border rounded-md">
                                    </div>

                                    <!-- State -->
                                    <div class="mb-4">
                                        <label for="state" class="block text-sm font-medium text-gray-600">State</label>
                                        <input type="text" id="state" name="state" class="mt-1 p-2 w-full border rounded-md">
                                    </div>

                                    <!-- Postal Code -->
                                    <div class="mb-4">
                                        <label for="postal_code" class="block text-sm font-medium text-gray-600">Postal Code</label>
                                        <input type="text" id="postal_code" name="postal_code" class="mt-1 p-2 w-full border rounded-md">
                                    </div>

                                    <!-- Country -->
                                    <div class="mb-4">
                                        <label for="country" class="block text-sm font-medium text-gray-600">Country</label>
                                        <select id="country" name="country" class="mt-1 p-2 w-full border rounded-md">
                                            <option value="Bangladesh" selected>Bangladesh</option>
                                            <option value="India">India</option>
                                            <option value="United States">United States</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Australia">Australia</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="Germany">Germany</option>
                                            <option value="France">France</option>
                                            <option value="Brazil">Brazil</option>
                                            <option value="China">China</option>
                                            <option value="Japan">Japan</option>
                                            <option value="South Korea">South Korea</option>
                                            <option value="Russia">Russia</option>
                                            <option value="Mexico">Mexico</option>
                                            <option value="South Africa">South Africa</option>
                                            <option value="Nigeria">Nigeria</option>
                                            <option value="Argentina">Argentina</option>
                                            <option value="Italy">Italy</option>
                                            <option value="Spain">Spain</option>
                                            <option value="Netherlands">Netherlands</option>
                                        </select>
                                    </div>


                                    <!-- Phone Number -->
                                    <div class="mb-4">
                                        <label for="phone_number" class="block text-sm font-medium text-gray-600">Phone Number</label>
                                        <input type="tel" id="phone_number" name="phone_number" class="mt-1 p-2 w-full border rounded-md">
                                    </div>
                                </form>

                        </div>
                        <div class="w-full mx-auto rounded-lg bg-white border border-gray-200 text-gray-800 font-light mb-6">
                            <div class="w-full p-3 border-b border-gray-200">
                                <div class="mb-5">
                                    <label for="type1" class="flex items-center cursor-pointer">
                                        <input type="radio" class="form-radio h-5 w-5 text-indigo-500" name="type" id="type1" checked>
                                        <img src="https://leadershipmemphis.org/wp-content/uploads/2020/08/780370.png" class="h-6 ml-3">
                                    </label>
                                </div>
                                <div>
                                    <div class="mb-3">
                                        <label class="text-gray-600 font-semibold text-sm mb-2 ml-1">Name on card</label>
                                        <div>
                                            <input class="w-full px-3 py-2 mb-1 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors" placeholder="John Smith" type="text"/>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-gray-600 font-semibold text-sm mb-2 ml-1">Card number</label>
                                        <div>
                                            <input class="w-full px-3 py-2 mb-1 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors" placeholder="0000 0000 0000 0000" type="text"/>
                                        </div>
                                    </div>
                                    <div class="mb-3 -mx-2 flex items-end">
                                        <div class="px-2 w-1/4">
                                            <label class="text-gray-600 font-semibold text-sm mb-2 ml-1">Expiration date</label>
                                            <div>
                                                <select class="form-select w-full px-3 py-2 mb-1 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors cursor-pointer">
                                                    <option value="01">01 - January</option>
                                                    <option value="02">02 - February</option>
                                                    <option value="03">03 - March</option>
                                                    <option value="04">04 - April</option>
                                                    <option value="05">05 - May</option>
                                                    <option value="06">06 - June</option>
                                                    <option value="07">07 - July</option>
                                                    <option value="08">08 - August</option>
                                                    <option value="09">09 - September</option>
                                                    <option value="10">10 - October</option>
                                                    <option value="11">11 - November</option>
                                                    <option value="12">12 - December</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="px-2 w-1/4">
                                            <select class="form-select w-full px-3 py-2 mb-1 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors cursor-pointer">
                                                <option value="2020">2020</option>
                                                <option value="2021">2021</option>
                                                <option value="2022">2022</option>
                                                <option value="2023">2023</option>
                                                <option value="2024">2024</option>
                                                <option value="2025">2025</option>
                                                <option value="2026">2026</option>
                                                <option value="2027">2027</option>
                                                <option value="2028">2028</option>
                                                <option value="2029">2029</option>
                                            </select>
                                        </div>
                                        <div class="px-2 w-1/4">
                                            <label class="text-gray-600 font-semibold text-sm mb-2 ml-1">Security code</label>
                                            <div>
                                                <input class="w-full px-3 py-2 mb-1 border border-gray-200 rounded-md focus:outline-none focus:border-indigo-500 transition-colors" placeholder="000" type="text"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full p-3">
                                <label for="type2" class="flex items-center cursor-pointer">
                                    <input type="radio" class="form-radio h-5 w-5 text-indigo-500" name="type" id="type2">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" width="80" class="ml-3"/>
                                </label>
                            </div>
                        </div>
                        <div>
                            <button class="block w-full max-w-xs mx-auto bg-indigo-500 hover:bg-indigo-700 focus:bg-indigo-700 text-white rounded-lg px-3 py-2 font-semibold"><i class="mdi mdi-lock-outline mr-1"></i> PAY NOW</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
    </div>

    <?php include './components/footer/footer.php'; ?>
</body>
</html>