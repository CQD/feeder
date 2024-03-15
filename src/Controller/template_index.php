<!DOCTYPE html>
<html>
<head>
<title>CQD 的Feed 產生器</title>
<style>
body{
    background-color: #222;
    color:#ddd;
    padding-bottom: 5em;
}
a:link, a:visited {
    color: #3af;
    text-decoration: none;
}
code {
    color: #da0;
}
h1 {
    color: #fff;
    border-bottom: 1px solid #444;
}
h2 {
    color: #eee;
    border-bottom: 1px solid #333;
    margin-top: 2em;
}
</style>
</head>
<body>
<pre><?=$data['content']?></pre>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
document.querySelector('body').innerHTML = marked.parse(document.querySelector('pre').textContent);
</script>
</body>
</html>