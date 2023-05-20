/************************************************
        Common General Data
*************************************************/

const demoURL = 'https://cartproshop.com/demo/api'; //Demo Link
let fetchGeneralApiData;
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

    // console.log(data);
    // return;

    fetchGeneralApiData    = data;
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
        Bug API Data Load
*************************************************/



const loadBugsInfo = () => {
    let url = `${demoURL}/fetch-data-bugs`; //Demo Link
    fetch(url)
    .then(res => res.json())
    .then(data => displayBugInfo(data));
}


let fetchBugApiData;
const displayBugInfo = bugInfoData => {
    if (clientVersionNumber >= minimumRequiredVersion && demoVersionNumber === clientVersionNumber && bugUpdateEnable ===true && productMode==='DEMO') {
        if (demoBugNo > clientBugNo) {
            $('#bugSection').removeClass('d-none');
        }else{
            $('#noBug').removeClass('d-none');
        }
    }
    fetchBugApiData = bugInfoData;
}

const stringToNumberConvert = dataString => {
    const myArray = dataString.split(".");
    let versionString = "";
    myArray.forEach(element => {
        versionString += element;
    });
    let versionConvertNumber = parseInt(versionString);
    return versionConvertNumber;
}

loadBugsInfo();


/************************************************
        Bug Update
*************************************************/







