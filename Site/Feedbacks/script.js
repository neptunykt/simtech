window.addEventListener('load', _ => {
    // Метод для загрузки файла
    $(".download-file").click(function (e) {
        e.preventDefault();
        const fileName = e.target.innerText;
        const url = `/File/download?file=${fileName}`;
        console.log(url);
        fetch(url, { method: 'get' })
            .then(res => res.blob())
            .then(res => {
                const aElement = document.createElement('a');
                aElement.setAttribute('download', fileName);
                const href = URL.createObjectURL(res);
                aElement.href = href;
                aElement.setAttribute('target', '_blank');
                aElement.click();
                URL.revokeObjectURL(href);
            });
    });
});
