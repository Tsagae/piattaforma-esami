<script>
    function sleep(s) {
        return new Promise(resolve => setTimeout(resolve, s * 1000));
    }
    sleep(<?= esc($delay) ?>).then(() => {
        window.location.replace("<?= esc($url) ?>");
    });
</script>