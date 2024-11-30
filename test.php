1. every 10s ~ 1 mins --> select * from table (join auction and item) where closing date < $now
2. based on auction id from 1) --> find the higest bid price from the bidding tables query for each acution id
3. udpate uaction auction table --> for highest bid price and bid winner
4. insert into auction -> values -> 1. auction winner 2. auction final price
5. email buyer and seller
   -- query select buyer and seller emails
   -- mail php --> send email to buyre and seller