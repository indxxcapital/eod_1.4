for /f "tokens=2 delims==" %%a in ('wmic OS Get localdatetime /value') do set "dt=%%a"
set "YY=%dt:~2,2%" & set "YYYY=%dt:~0,4%" & set "MM=%dt:~4,2%" & set "DD=%dt:~6,2%"
set "HH=%dt:~8,2%" & set "Min=%dt:~10,2%" & set "Sec=%dt:~12,2%"
set "fullstamp=%YYYY%-%MM%-%DD%_%HH%-%Min%-%Sec%"
mysqldump --opt -hlocalhost -uadmin_icai141 -pIndxxb3930@db admin_icai141 > C:/Inetpub/vhosts\ip-198-12-158-159.secureserver.net/httpdocs/ical1.4.1/files/db-backup/closing_backup_admin_icai141_%fullstamp%.sql