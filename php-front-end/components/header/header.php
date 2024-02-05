<!-- header-top.php -->

<div class="block">
    <?php include 'topbar.php'; ?>
    <div class="w-[90%] mx-auto pb-3 border-b border-gray-200 pt-2">
        <div class="flex justify-between items-center">
            <div class="logo w-[15%] text-2xl font-bold text-gray-800">
                <a href="/">Logo</a>
            </div>

            <div class="nav w-[60%]">
                <?php include 'search.php'; ?>
            </div>

            <div class="header-actions flex justify-end gap-2 items-center w-[25%]">
                <?php if (!$userId) { ?>
                    <a href="/login">
                        <span class="bg-slate-800 rounded-full py-2 px-4 border-none text-sm text-white hover:bg-slate-900 transition duration-300 cursor-pointer">
                            Signin / Signup
                        </span>
                    </a>
                <?php } ?>
                <?php if ($userId) { ?>
                    <div class="user-p flex items-center justify-end relative">
                        <div class="d-u w-11 h-11 rounded-full bg-white border border-gray-200 p-1 flex justify-center items-center text-white cursor-pointer"
                            onclick="toggleUserDropdown()">
                            <img src="http://localhost/reactcrud/backend/auth/<?php echo $userPhoto; ?>" alt="User"
                                class="w-10 h-10 rounded-full object-cover">
                        </div>
                        <?php if ($user) { ?>
                            <div class="main-u absolute right-0 top-12 w-72 rounded-md px-4 pt-5 pb-4 shadow-md border border-gray-300 bg-white z-50">
                                <!-- ... rest of the PHP code for user dropdown ... -->
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="mainNav flex justify-center items-center pt-2">
        <?php include 'nav.php'; ?>
    </div>
</div>
