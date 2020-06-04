<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page not found</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
        body {
            background: #f5f5f5;
            font-family: 'Noto Sans', sans-serif;
            margin: 0;
            color: #4c5667;
            overflow-x: hidden !important;
            padding-top:40px;
        }

        .message-box h1 {
            color: #252932;
            font-size: 98px;
            font-weight: 700;
            line-height: 98px;
            text-shadow: rgba(61, 61, 61, 0.3) 1px 1px, rgba(61, 61, 61, 0.2) 2px 2px, rgba(61, 61, 61, 0.3) 3px 3px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <svg class="svg-box" width="380px" height="500px" viewBox="0 0 837 1045" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <path d="M353,9 L626.664028,170 L626.664028,487 L353,642 L79.3359724,487 L79.3359724,170 L353,9 Z" id="Polygon-1" stroke="#3bafda" stroke-width="6"></path>
                    <path d="M78.5,529 L147,569.186414 L147,648.311216 L78.5,687 L10,648.311216 L10,569.186414 L78.5,529 Z" id="Polygon-2" stroke="#7266ba" stroke-width="6"></path>
                    <path d="M773,186 L827,217.538705 L827,279.636651 L773,310 L719,279.636651 L719,217.538705 L773,186 Z" id="Polygon-3" stroke="#f76397" stroke-width="6"></path>
                    <path d="M639,529 L773,607.846761 L773,763.091627 L639,839 L505,763.091627 L505,607.846761 L639,529 Z" id="Polygon-4" stroke="#00b19d" stroke-width="6"></path>
                    <path d="M281,801 L383,861.025276 L383,979.21169 L281,1037 L179,979.21169 L179,861.025276 L281,801 Z" id="Polygon-5" stroke="#ffaa00" stroke-width="6"></path>
                </g>
            </svg>
        </div>
        <div class="col-sm-6">
            <div class="message-box">
                <h1 class="m-b-0">404</h1>
                <p class="text-danger">Page not found.</p>
                <p>Lo sentimos , no podemos encontrar lo que buscas. Por favor revisa tu URL</p>
                <div class="buttons-con">
                    <div class="action-link-wrap">
                        <a href="<?=getUrl('/')?>" class="btn btn-custom btn-info waves-effect waves-light m-t-20">Volver al Inicio</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>