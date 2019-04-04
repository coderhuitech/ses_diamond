Select x.bill_no, x.package, sum(x.amount)+ sum(x.amount)*12.36/100+10 as total, x.bill_date, x.vcno, x.customer_name
from (SELECT bd.bill_no,
       bd.channel_name AS package,
       bd.channel_amount AS amount,
       bm.bill_date,
       bm.vcno,
       cust.customer_name
  FROM bill_details bd, bill_master bm, customers cust
 WHERE     bd.bill_no = bm.bill_no
       AND cust.customer_vcno = bm.vcno
       AND bm.bill_date BETWEEN '2013-08-14' AND '2013-08-14'
  union all
 SELECT bm.bill_no,
       pl.plan_name AS package,
       pl.plan_amount AS amount,
       bm.bill_date,
        bm.vcno,
       cust.customer_name
  FROM bill_master bm, plans pl, customers cust
 WHERE     bm.plan_id = pl.plan_id  AND cust.customer_vcno = bm.vcno
       AND bm.bill_date BETWEEN '2013-08-14' AND '2013-08-14'
union all
SELECT bm.bill_no,
       'Other Charges' AS package,
       bm.other_charges AS amount,
       bm.bill_date,
        bm.vcno,
       cust.customer_name
  FROM bill_master bm,customers cust
 WHERE cust.customer_vcno = bm.vcno and bm.bill_date BETWEEN '2013-08-14' AND '2013-08-14') as x
 group by customer_name
