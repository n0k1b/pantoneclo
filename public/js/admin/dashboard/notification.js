/************************************************
        Common General Data
*************************************************/
const demoURL = 'https://cartproshop.com/demo/api'; //Demo Link
let productMode;
let clientVersionNumber;
let clientBugNo;
let demoVersionString;
let demoVersionNumber;
let demoBugNo;
let minimumRequiredVersion;
let latestVersionUpgradeEnable;
let latestVersionDBMigrateEnable;
let bugUpdateEnable;
let bugDBMigrateEnable;

const loadGeneralData = () => {
    let url = `${demoURL}/fetch-data-general`;
    fetch(url)
    .then(res => res.json())
    .then(data => displayGeneralData(data));
}

const displayGeneralData = data => {
    productMode            = data.general.product_mode;
    clientVersionNumber    = stringToNumberConvert(clientCurrrentVersion);
    clientBugNo            = parseInt(clientCurrrentBugNo);
    demoVersionString      = data.general.demo_version;
    demoVersionNumber      = stringToNumberConvert(demoVersionString);
    demoBugNo              = data.general.demo_bug_no;
    minimumRequiredVersion = stringToNumberConvert(data.general.minimum_required_version);
    latestVersionUpgradeEnable   = data.general.latest_version_upgrade_enable;
    latestVersionDBMigrateEnable = data.general.latest_version_db_migrate_enable;
    bugUpdateEnable        = data.general.bug_update_enable;
    bugDBMigrateEnable     = data.general.bug_db_migrate_enable;
}

loadGeneralData();

/************************************************
                     Version Upgrade
*************************************************/

// Version Upgrade Notification
const loadVersionUpgradeData = () => {
    let url = `${demoURL}/fetch-data-upgrade`;
    fetch(url)
    .then(res => res.json())
    .then(data => displayUpgradeNotification(data));
}

let fetchApiData;
const displayUpgradeNotification = (data) => {
    if (clientVersionNumber >= minimumRequiredVersion && latestVersionUpgradeEnable===true && productMode==='DEMO') {
        // Announce
        if (demoVersionNumber > clientVersionNumber) {
            $('#alertSection').removeClass('d-none');
            $('#newVersionNo').text(demoVersionString);
            $('#announce').removeClass('d-none');
        }
        // Congratulation
        else if (localStorage.getItem('version_upgrade_status')=='done' && (demoVersionNumber === clientVersionNumber)) {
            // console.log(sessionStorage.getItem('version_upgrade_status'));
            $('#alertSection').removeClass('d-none').addClass('alert-info');
            $('#congratulation').removeClass('d-none');
        }
    }
}

loadVersionUpgradeData();


/************************************************
                     Auto Bug Fixed
*************************************************/


// Auto Bug Notification
const loadBugsInfo = () => {
    let url = `${demoURL}/fetch-data-bugs`;
    fetch(url)
    .then(res => res.json())
    .then(data => displayBugNotification(data));
}

let fetchBugApiData;
const displayBugNotification = (data) => {
    if (clientVersionNumber >= minimumRequiredVersion && demoVersionNumber === clientVersionNumber && bugUpdateEnable===true && productMode==='DEMO') {
        // Alert
        if (demoBugNo > clientBugNo) {
            $('#alertBugSection').removeClass('d-none');
            $('#alertBug').removeClass('d-none');
        }
        // Congratulation
        else if (localStorage.getItem('bug_status')=='done' && (clientBugNo === demoBugNo)) {
            console.log(localStorage.getItem('bug_status'));
            $('#alertBugSection').removeClass('d-none').css("background-color", "rgb(212,237,218)");
            $('#congratulationBug').removeClass('d-none');
        }
    }
}

loadBugsInfo();



/******************************************************************
        String to Number Conversion of Version from env
*******************************************************************/

const stringToNumberConvert = dataString => {
    const myArray = dataString.split(".");
    let versionString = "";
    myArray.forEach(element => {
        versionString += element;
    });
    let versionConvertNumber = parseInt(versionString);
    return versionConvertNumber;
}


$('#closeButtonUpgrade').on('click',function(){
    localStorage.removeItem('version_upgrade_status');
});
$('#closeButtonBugUpdate').on('click',function(){
    localStorage.removeItem('bug_status');
});
