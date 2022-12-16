<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>

<body>
    <div class="container">
        <div class="row mt-5">
            <table class="table table-sm table-striped">
                <tr>
                    <td>Seat</td>
                    <td>:</td>
                    <td>
                        <input type="checkbox" name="a1" id="a1" value="15000"> A1
                        <input type="checkbox" name="b1" id="b1" value="20000"> B1
                        <input type="checkbox" name="c1" id="c1" value="35000"> C1
                    </td>
                </tr>
                <tr>
                    <td>Harga Tiket</td>
                    <td>:</td>
                    <td>
                        <input type="text" name="harga" id="harga" value="0" readonly="readonly">
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>

    <script>
    let harga = $('#harga').val();
    let a1;
    let b1;
    let c1;
    $(document).ready(function() {
        $("#a1").click(function() {
            a1 = $(this).val();
            if ($(this).is(":checked")) {
                harga = parseInt(harga) + parseInt(a1);
                $('#harga').val(harga);
            } else {
                harga = parseInt(harga) - parseInt(a1);
                $('#harga').val(harga);
            }
        });

        $("#b1").click(function() {
            b1 = $(this).val();
            if ($(this).is(":checked")) {
                harga = parseInt(harga) + parseInt(b1);
                $('#harga').val(harga);
            } else {
                harga = parseInt(harga) - parseInt(b1);
                $('#harga').val(harga);
            }
        });

        $("#c1").click(function() {
            c1 = $(this).val();
            if ($(this).is(":checked")) {
                harga = parseInt(harga) + parseInt(c1);
                $('#harga').val(harga);
            } else {
                harga = parseInt(harga) - parseInt(c1);
                $('#harga').val(harga);
            }
        });
    });
    </script>
</body>

</html>