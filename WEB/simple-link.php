<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/home.css">
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    .border__success {
        /* border: 2px solid #01ff00 !important;
    border-radius: 8px !important; */
    }
</style>

<body>
    <div class="bg"></div>

    <style>
        .border__success .s-front {}

        .border__success img {
            width: 50px;
        }

        .border__success .s-back {
            display: none;
        }

        .border__success {
            height: 46px !important;
        }

        .border__success:hover .s-back {
            display: inherit;
        }

        .border__success:hover .s-front {
            display: none;
        }

        .border__success:hover .s-back {
            width: 80px;
        }

        /* 2 */
        .border__danger img {
            width: 50px;
            margin: auto;
        }

        .border__danger .d-back {
            display: none;
        }

        .btn {
            height: 60px !important;
        }

        .border__danger:hover .d-back {
            display: inherit;
        }

        .border__danger:hover .d-front {
            display: none;
        }

        .border__danger:hover .d-back {
            width: 90px;
        }

        .main-content .btn {
            transition: 0.5s;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn b {
            display: flex;
            align-items: center;
            flex-direction: column;
            font-size: 15px;
            text-transform: capitalize;
        }
    </style>
    <section class="main">

        <div class="disc_steam_box main-content">
            <h4 class="text-white text-start">LINK YOUR ACCOUNT</h4>
            <p class="text-white text-start">LINK TO GET ACCESS TO IN-GAME REWARDS AND A DISCORD ROLE</p>
            <div class="mt-5" style="margin-top: 6rem !important;">
                <p class="text-white text-center">TYPE /AUTH  IN-GAME TO CLAIM REWARDS</p>

                <div class="content-btn-col d-flex justify-content-center">

                    <div class="text-white">
                        Step 1: Steam
                        <a href="#" class="btn border__success"><b>
                                <img class="s-front" src="./images/s-front.png" alt="" srcset="">
                                <img class="s-back" src="./images/s-back.png" alt="" srcset="">
                            </b>
                            <span></span><span></span><span></span><span></span></a>

                    </div>
                    <div class="text-white">
                        Step 2: Discord
                        <a href="https://link.frostbite.gg/" class="btn border__danger"><b>
                                <img class="d-front" src="./images/d-front.png" />
                                <img class="d-back" src="./images/d-back.png" />
                            </b>
                            <span></span><span></span><span></span><span></span></a>

                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select the hamburger button and the navbar collapse div
        const navbarToggler = document.querySelector('.navbar-toggler');
        const navbarCollapse = document.querySelector('#navbarTogglerDemo01');

        // Add an event listener to the toggler button to toggle the navbar
        navbarToggler.addEventListener('click', function() {
            // Toggle the 'collapse' class on the navbar
            navbarCollapse.classList.toggle('collapse');

            // Optional: You can add/remove an 'aria-expanded' attribute for accessibility
            const isExpanded = navbarCollapse.classList.contains('collapse') ? 'false' : 'true';
            navbarToggler.setAttribute('aria-expanded', isExpanded);
        });
    });
</script>

</html>