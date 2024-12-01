<?php

// display_time_remaining:
// Helper function to help figure out what time to display
// function display_time_remaining($interval) {

//     if ($interval->days == 0 && $interval->h == 0) {
//       // Less than one hour remaining: print mins + seconds:
//       $time_remaining = $interval->format('%im %Ss');
//     }
//     else if ($interval->days == 0) {
//       // Less than one day remaining: print hrs + mins:
//       $time_remaining = $interval->format('%hh %im');
//     }
//     else {
//       // At least one day remaining: print days + hrs:
//       $time_remaining = $interval->format('%ad %hh');
//     }

//   return $time_remaining;

// }

// print_listing_li:
// This function prints an HTML <li> element containing an auction listing
// function print_listing_li($item_id, $title, $desc, $price, $num_bids, $end_time)
// {
//   // Truncate long descriptions
//   if (strlen($desc) > 250) {
//     $desc_shortened = substr($desc, 0, 250) . '...';
//   } else {
//     $desc_shortened = $desc;
//   }

//   // Fix language of bid vs. bids
//   $bid = ($num_bids == 1) ? ' bid' : ' bids';

//   // Calculate time to auction end
//   $now = new DateTime();
//   if ($now > $end_time) {
//     $time_remaining = 'This auction has ended';
//   } else {
//     // Get interval:
//     $time_to_end = date_diff($now, $end_time);
//     $time_remaining = format_time_remaining($time_to_end) . ' remaining';
//   }

//   // Output the HTML for this listing
//   echo('
//   <li class="list-group-item d-flex justify-content-between">
//   <div class="p-2 mr-5"><h5><a href="listing.php?item_id=' . $item_id . '">' . $title . '</a></h5>' . $desc_shortened . '</div>
//   <div class="text-center text-nowrap"><span style="font-size: 1.5em">£' . number_format($price, 2) . '</span><br/>' . $num_bids . $bid . '<br/>' . $time_remaining . '</div>
//   </li>'
//   );
// }

// // Define the custom format_time_remaining function
// function format_time_remaining($time_to_end) {
//   $days = $time_to_end->days;
//   $hours = $time_to_end->h;
//   $minutes = $time_to_end->i;
//   $seconds = $time_to_end->s;

//   return "{$days}d {$hours}h {$minutes}m {$seconds}s";
// }

function display_time_remaining($interval) {
  // 格式化剩余时间为天、小时、分钟和秒
  $time_remaining = $interval->format('%ad %hh %im %Ss');
  return $time_remaining;
}

// function print_listing_li($item_id, $title, $desc, $price, $num_bids, $end_time, $item_picture)
// {
//     // Truncate long descriptions
//     if (strlen($desc) > 250) {
//         $desc_shortened = substr($desc, 0, 250) . '...';
//     } else {
//         $desc_shortened = $desc;
//     }

//     // Fix language of bid vs. bids
//     $bid = ($num_bids == 1) ? ' bid' : ' bids';

//     // Calculate initial time remaining
//     $now = new DateTime();
//     if ($now > $end_time) {
//         $time_remaining = 'This auction has ended';
//     } else {
//         $time_to_end = date_diff($now, $end_time);
//         $time_remaining = display_time_remaining($time_to_end);
//     }

//     // Convert end_time to a JavaScript-compatible format
//     $end_time_js = $end_time->format('Y-m-d H:i:s');

//     // Output the HTML for this listing
//     echo('
//     <li class="list-group-item d-flex justify-content-between">
//         <div class="p-2 mr-5"><h5><a href="listing.php?item_id=' . $item_id . '">' . $title . '</a></h5>' . $desc_shortened . '</div>
//         <div class="text-center text-nowrap"><span style="font-size: 1.5em">£' . number_format($price, 2) . '</span><br/>' . $num_bids . $bid . '<br/>
//         <span id="time_remaining_' . $item_id . '">' . $time_remaining . '</span></div>
//     </li>
//     ');

//     // JavaScript logic for dynamic countdown
//     echo('
//     <script>
//         (function() {
//             var endTime = new Date("' . $end_time_js . '").getTime();
//             var countdownElement = document.getElementById("time_remaining_' . $item_id . '");

//             function updateCountdown() {
//                 var now = new Date().getTime();
//                 var timeRemaining = endTime - now;

//                 if (timeRemaining > 0) {
//                     var days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
//                     var hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
//                     var minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
//                     var seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

//                     // Update the display with the remaining time
//                     countdownElement.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
//                 } else {
//                     countdownElement.innerHTML = "This auction has ended";
//                     clearInterval(timer);
//                 }
//             }

//             var timer = setInterval(updateCountdown, 1000);
//             updateCountdown(); // Call immediately to set the initial value
//         })();
//     </script>
//     ');
// }

function print_listing_li($item_id, $title, $desc, $price, $num_bids, $end_time, $item_picture)
{
    // 确保 $item_picture 有效，如果为 null，则设置为默认图片路径

    $item_picture = $item_picture ?? 'images/default.jpg';

    // Truncate long descriptions
    if (strlen($desc) > 250) {
        $desc_shortened = substr($desc, 0, 250) . '...';
    } else {
        $desc_shortened = $desc;
    }

    // Fix language of bid vs. bids
    $bid = ($num_bids == 1) ? ' bid' : ' bids';

    // Calculate initial time remaining
    $now = new DateTime();
    if ($now > $end_time) {
        $time_remaining = 'This auction has ended';
    } else {
        $time_to_end = date_diff($now, $end_time);
        $time_remaining = display_time_remaining($time_to_end);
    }

    // Convert end_time to a JavaScript-compatible format
    $end_time_js = $end_time->format('Y-m-d H:i:s');

    // Output the HTML for this listing
    echo('
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <table style="width:100%">
            <tr>
                <td width="30%">
                <div class="p-2 mr-5">
                    <h5><a href="listing.php?item_id=' . $item_id . '">' . $title . '</a></h5>
                    <p>' . $desc_shortened . '</p>
                </div>
                </td>
                <td width="50%">
                <div class="p-2">
                    <img src="' . htmlspecialchars($item_picture) . '" alt="' . htmlspecialchars($title) . '" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                </div>
                </td>
                <td width="20%">
                <div class="text-center text-nowrap">
                    <span style="font-size: 1.5em">£' . number_format($price, 2) . '</span><br/>' . $num_bids . $bid . '<br/>
                    <span id="time_remaining_' . $item_id . '">' . $time_remaining . '</span>
                </div>
                </td>
            </tr>
        </table>

    </li>
    ');

    // JavaScript logic for dynamic countdown
    echo('
    <script>
        (function() {
            var endTime = new Date("' . $end_time_js . '").getTime();
            var countdownElement = document.getElementById("time_remaining_' . $item_id . '");

            function updateCountdown() {
                var now = new Date().getTime();
                var timeRemaining = endTime - now;

                if (timeRemaining > 0) {
                    var days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

                    // Update the display with the remaining time
                    countdownElement.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                } else {
                    countdownElement.innerHTML = "This auction has ended";
                    clearInterval(timer);
                }
            }

            var timer = setInterval(updateCountdown, 1000);
            updateCountdown(); // Call immediately to set the initial value
        })();
    </script>
    ');
}


function auctionTImer($endTime){
    echo('
        <div id="auctionTimer"></div>
    ');

    // JavaScript logic for dynamic countdown
    echo('
    <script>
        (function() {
            var endTime = new Date("' . $endTime . '").getTime();
            var countdownElement = document.getElementById("auctionTimer");

            function updateCountdown() {
                var now = new Date().getTime();
                var timeRemaining = endTime - now;
                console.log(new Date())
                if (timeRemaining > 0) {    
                    var days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

                    // Update the display with the remaining time
                    countdownElement.innerHTML = `Auction ends on ' . $endTime . '<br> ${days}d ${hours}h ${minutes}m ${seconds}s`;
                } else {
                    countdownElement.innerHTML = `This auction has ended on (' . $endTime . ')`;
                    clearInterval(timer);
                }
            }

            var timer = setInterval(updateCountdown, 1000);
            updateCountdown(); // Call immediately to set the initial value
        })();
    </script>
    ');
}

function uuid4() {
  /* 32 random HEX + space for 4 hyphens */
  $out = bin2hex(random_bytes(18));

  // $out[8]  = "-";
  // $out[13] = "-";
  // $out[18] = "-";
  // $out[23] = "-";

  // /* UUID v4 */
  // $out[14] = "4";
  
  // /* variant 1 - 10xx */
  // $out[19] = ["8", "9", "a", "b"][random_int(0, 3)];

  return $out;
}

function get_bid_records($item_id) {
  // Database connection
  $conn = new mysqli("localhost", "root", "", "auction_system");
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  // Query bid records for the given item ID
  $sql = "SELECT BidAmount, TimeOfBid 
          FROM bid 
          WHERE ItemID = ? 
          ORDER BY TimeOfBid DESC"; // Order by most recent bid
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $item_id); // ItemID is a varchar(10)
  $stmt->execute();
  $result = $stmt->get_result();

  // Fetch all results
  $bids = [];
  while ($row = $result->fetch_assoc()) {
      $bids[] = $row;
  }

  $stmt->close();
  $conn->close();

  return $bids; // Return the bid records as an array
}


?>