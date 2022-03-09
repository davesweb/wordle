<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Wordle</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.4.0/styles/default.min.css">
        <style>
            p, h1, h2, li, table, th, td {
                font-family: 'Lato', sans-serif;
                font-family: 'Poppins', sans-serif;
            }
            p, h1, h2, ul {
                margin: 0 0 0.75em;
            }
            pre {
                margin-bottom: 0.75em;
            }
            code {
                font-size: 1rem;
                color: deeppink;
            }
            table {
                border: 1px solid #666;
                font-size: 1rem;
                margin-bottom: 0.75em;
            }
            table th, table td {
                padding: 0.5em;
            }
            table tr:not(:last-child) th,  table tr:not(:last-child) td {
                border-bottom: 1px solid #eee;
            }
            table td:not(:last-child) {
                border-right: 1px solid #eee;
            }
            table th:not(:last-child) {
                border-right: 1px solid #eee;
            }
            .left {
                text-align: left;
            }
            .right {
                text-align: right;
            }
            a {
                text-decoration: none;
            }
            a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div style="max-width: 960px; margin-left: auto; margin-right: auto; padding: 1em;">
            @yield('content')
        </div>
    </body>
</html>
