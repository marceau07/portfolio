$DateInstallation = (Get-WmiObject -class Cim_OperatingSystem).installdate 

[System.Management.ManagementDateTimeConverter]::ToDateTime($DateInstallation)> DateInstallation.txt