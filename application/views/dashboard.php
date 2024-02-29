<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<button type="button" id="cari">Cari</button>



<h1>hello word</h1>
<script>
    const Bcari         = document.getElementById('cari');
    const getMemory     = localStorage.getItem('session'); 

    const session       = JSON.parse(getMemory)

    const token         = session.token;
    const exp           = session.exp;
    const data          = session.result;

    if(token && exp){
        const expirationDate = new Date(exp * 1000); 
        if (expirationDate > new Date()) {
            // Token masih valid, lakukan sesuatu (contohnya, arahkan ke halaman dashboard)
            console.log(expirationDate, new Date())
        } else {
            // Token telah kedaluwarsa, lakukan sesuatu (contohnya, arahkan ke halaman logout) 
            localStorage.removeItem('session'); 
            window.location.href = '<?= base_url('login') ?>';
        }

    }else {
        // Token atau waktu kedaluwarsa tidak ada, lakukan sesuatu (contohnya, arahkan ke halaman login)
        window.location.href = '<?= base_url('login') ?>';
    } 
 

    Bcari.addEventListener('click', async function(){
         const getPegawai       = await cariPegawai();
         console.log(getPegawai)
    });

    async function cariPegawai()
    {
        try {
            const response = await fetch("<?= base_url('Pegawai') ?>", {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `${token}` // Menyertakan token di header Authorization
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error("Error:", error);
        }
    }
   
</script>
</body>
</html>