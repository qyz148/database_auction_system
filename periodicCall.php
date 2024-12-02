<?php 
    echo '
        <div>
            <script>
                fetch(window.location.origin+"/inbox.php?check_auctions=true").then(async r => console.log("cao"))
                setInterval(function() {
                    $.ajax({    
                        url: "inbox.php?check_auctions=true",
                        type: "GET",
                        success: function(data) {
                            // console.log("Auction check complete:", data);
                        }
                    });
                }, 10000);
            </script>
        </div>
    ';
?>
