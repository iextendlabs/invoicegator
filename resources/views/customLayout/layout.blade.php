<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <!-- Bootatrp Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- custom styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <title>@yield('title')</title>
    <style>
        a:hover {
            cursor: pointer;
        }

        /* handle pagination front-end */

        body > div > div.row.mt-3 > div > div > nav > div.hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between > div:nth-child(2){
            display: none;
        }

        body > div > div.row.mt-3 > div > div > nav > div.hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between > div:nth-child(1){
            margin-top: 17px;
            margin-left: 12px;
            font-size: 16px;
            /* display: none; */
        }

        body > div > div.row.mt-2 > div > nav > div.hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between > div:nth-child(2){
            display: none;
        }

        /* time input without am / pm */

        .without_ampm::-webkit-datetime-edit-ampm-field {
            display: none;
        }
        input[type=time]::-webkit-clear-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        -o-appearance: none;
        -ms-appearance:none;
        appearance: none;
        margin: -10px; 
        }


    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            
            @yield('navbar')

        </div>

        @yield('main-content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <script>
        $(function(){
            $('#button').click(function(){
                window.print();
                // print specific div
                // $("#printPreview").print();
            });
            
            // Invoice Generate Buttons
            $('#fixedAmountForm').hide();
            $("#fixedHoursForm").hide();
            $("#dateForm").show();

            // fixed amount
            $("#fixedAmount").click(function(){
                $(this).css({'background-color':'blue', 'color':'white'});
                $("#dateForm").hide();
                $("#fixedHoursForm").hide();
                $("#fixedAmountForm").show();
            });

            // date-wise invoice
            $("#dateWiseInvoice").click(function(){
                $(this).css({'background-color':'green', 'color':'white'});
                $("#dateForm").show();
                $("#fixedHoursForm").hide();
                $("#fixedAmountForm").hide();
            });

            // fixed hours
            $("#fixedHours").click(function(){
                $(this).css({'background-color':'darkblue', 'color':'white'});
                $("#fixedHoursForm").show();
                $("#fixedAmountForm").hide();
                $("#dateForm").hide();                
            });


            
        });
    </script>
</body>

</html>