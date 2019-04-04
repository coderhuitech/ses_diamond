%Y	 4-digit year
%y	 2-digit year	 for year < 70 the century is 20th, otherwise 19th
%b	 Abbreviated month (Jan - Dec)
%M	 Month name (January - December)
%m	 Month (0 - 12)	 Zero month supported by MySQL
%a	 Abbreviated day (Sun - Sat)
%d	 Day (0 - 31)	 Zero day supported by MySQL
%H	 Hour (0 - 23)
%h	 Hour (01 - 12)
%i	 Minutes (0 - 59)
%s	 Seconds (0 - 59)


SELECT date_format(lc_receipt_date,'%d/%m/%Y %H:%i') as lc_receipt_date;
