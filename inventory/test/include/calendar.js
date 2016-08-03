var calendar_included=true;

// Title: Tigra Calendar
// URL: http://www.softcomplex.com/products/tigra_calendar/
// Version: 3.3 (European date format)
// Date: 09/01/2005 (mm/dd/yyyy)
// Note: Permission given to use this script in ANY kind of applications if
//    header lines are left unchanged.
// Note: Script consists of two files: calendar?.js and calendar.html

// if two digit year input dates after this year considered 20 century.
var NUM_CENTYEAR = 30;
// is time input control required by default
var BUL_TIMECOMPONENT = false;
// are year scrolling buttons required by default
var BUL_YEARSCROLL = true;

var calendars = [];
var RE_NUM = /^\-?\d+$/;

function calendar(obj_target,fldName,record_id,value) {

	// assigning methods
	this.gen_date = cal_gen_date;
	this.gen_time = cal_gen_time;
	this.gen_tsmp = cal_gen_tsmp;
	this.prs_date = cal_prs_date;
	this.prs_time = cal_prs_time;
	this.prs_tsmp = cal_prs_tsmp;
	this.popup    = cal_popup;

	// validate input parameters
	this.target = obj_target;
	this.targetvalue = value;
	this.targetfldname = fldName;
	this.targetrecord_id = record_id;
	this.time_comp = BUL_TIMECOMPONENT;
	this.year_scroll = BUL_YEARSCROLL;
	
	// register in global collections
	this.id = calendars.length;
	calendars[this.id] = this;
}

function cal_popup(str_datetime) {
	if (str_datetime) {
		this.dt_current = this.prs_tsmp(str_datetime);
	}
	else {
		this.dt_current = this.prs_tsmp(this.targetvalue);
		this.dt_selected = this.dt_current;
	} 
	
	if (!this.dt_current)
	{
		this.dt_current=new Date();
		this.dt_selected = this.dt_current;
	};

	var obj_calwindow = window.open(
		'calendar.html?datetime=' + this.dt_current.valueOf()+ '&id=' + this.id,
		'Calendar', 'width=230,height=230,status=no,resizable=no,top=200,left=200,dependent=yes,alwaysRaised=yes'
	);
	obj_calwindow.opener = window;
	obj_calwindow.focus();
}

// timestamp generating function
function cal_gen_tsmp(dt_datetime) {
	return(this.gen_date(dt_datetime) + ' ' + this.gen_time(dt_datetime));
}

// date generating function
function cal_gen_date(dt_datetime) {
	return (
		(dt_datetime.getDate() < 10 ? '0' : '') + dt_datetime.getDate() + "-"
		+ (dt_datetime.getMonth() < 9 ? '0' : '') + (dt_datetime.getMonth() + 1) + "-"
		+ dt_datetime.getFullYear()
	);
}
// time generating function
function cal_gen_time(dt_datetime) {
	return (
		(dt_datetime.getHours() < 10 ? '0' : '') + dt_datetime.getHours() + ":"
		+ (dt_datetime.getMinutes() < 10 ? '0' : '') + (dt_datetime.getMinutes()) + ":"
		+ (dt_datetime.getSeconds() < 10 ? '0' : '') + (dt_datetime.getSeconds())
	);
}

// timestamp parsing function
function cal_prs_tsmp(str_datetime) {
	// if no parameter specified return current timestamp
	if (!str_datetime)
		return (new Date());

	// if positive integer treat as milliseconds from epoch
	if (RE_NUM.exec(str_datetime))
		return new Date(str_datetime);
		
	// else treat as date in string format
	var arr_datetime = str_datetime.split(' ');
	return this.prs_time(arr_datetime[1], this.prs_date(arr_datetime[0]));
}

// date parsing function
function cal_prs_date(str_date) {

	var arr_date = str_date.split('-');

	if (arr_date.length != 3) return cal_error ("Invalid date format: '" + str_date + "'.\nFormat accepted is dd-mm-yyyy.");
	if (!arr_date[0]) return cal_error ("Invalid date format: '" + str_date + "'.\nNo day of month value can be found.");
	if (!RE_NUM.exec(arr_date[0])) return cal_error ("Invalid day of month value: '" + arr_date[0] + "'.\nAllowed values are unsigned integers.");
	if (!arr_date[1]) return cal_error ("Invalid date format: '" + str_date + "'.\nNo month value can be found.");
	if (!RE_NUM.exec(arr_date[1])) return cal_error ("Invalid month value: '" + arr_date[1] + "'.\nAllowed values are unsigned integers.");
	if (!arr_date[2]) return cal_error ("Invalid date format: '" + str_date + "'.\nNo year value can be found.");
	if (!RE_NUM.exec(arr_date[2])) return cal_error ("Invalid year value: '" + arr_date[2] + "'.\nAllowed values are unsigned integers.");

	var dt_date = new Date();
	dt_date.setDate(1);

	if (arr_date[1] < 1 || arr_date[1] > 12) return cal_error ("Invalid month value: '" + arr_date[1] + "'.\nAllowed range is 01-12.");
	dt_date.setMonth(arr_date[1]-1);
	 
	if (arr_date[2] < 100) arr_date[2] = Number(arr_date[2]) + (arr_date[2] < NUM_CENTYEAR ? 2000 : 1900);
	dt_date.setFullYear(arr_date[2]);

	var dt_numdays = new Date(arr_date[2], arr_date[1], 0);
	dt_date.setDate(arr_date[0]);
	if (dt_date.getMonth() != (arr_date[1]-1)) return cal_error ("Invalid day of month value: '" + arr_date[0] + "'.\nAllowed range is 01-"+dt_numdays.getDate()+".");

	return (dt_date)
}

// time parsing function
function cal_prs_time(str_time, dt_date) {

	if (!dt_date) return null;
	var arr_time = String(str_time ? str_time : '').split(':');

	if (!arr_time[0]) dt_date.setHours(0);
	else if (RE_NUM.exec(arr_time[0]))
		if (arr_time[0] < 24) dt_date.setHours(arr_time[0]);
		else return cal_error ("Invalid hours value: '" + arr_time[0] + "'.\nAllowed range is 00-23.");
	else return cal_error ("Invalid hours value: '" + arr_time[0] + "'.\nAllowed values are unsigned integers.");
	
	if (!arr_time[1]) dt_date.setMinutes(0);
	else if (RE_NUM.exec(arr_time[1]))
		if (arr_time[1] < 60) dt_date.setMinutes(arr_time[1]);
		else return cal_error ("Invalid minutes value: '" + arr_time[1] + "'.\nAllowed range is 00-59.");
	else return cal_error ("Invalid minutes value: '" + arr_time[1] + "'.\nAllowed values are unsigned integers.");

	if (!arr_time[2]) dt_date.setSeconds(0);
	else if (RE_NUM.exec(arr_time[2]))
		if (arr_time[2] < 60) dt_date.setSeconds(arr_time[2]);
		else return cal_error ("Invalid seconds value: '" + arr_time[2] + "'.\nAllowed range is 00-59.");
	else return cal_error ("Invalid seconds value: '" + arr_time[2] + "'.\nAllowed values are unsigned integers.");

	dt_date.setMilliseconds(0);
	return dt_date;
}

function cal_error (str_message) {
//	alert (str_message);
	return null;
}

function show_calendar(func,fldName,record_id,value,time,sundayfirst)
{
	var cal = new calendar(func,fldName,record_id,value);
	cal.year_scroll = false;
	cal.time_comp = time; 
	cal.sunday_first = sundayfirst;
	cal.popup();
}


function parse_datetime(str,dmy)
{
	var re=/\d+/g;
	var arr=str.match(re);
	var dt;
	if(arr==null || arr.length<3)
		return null;
	while(arr.length<6)
		arr[arr.length]=0;
	if(dmy==1)
		dt = new Date(arr[2],arr[1]-1,arr[0],arr[3],arr[4],arr[5]);
	else if(dmy==0)
		dt = new Date(arr[2],arr[0]-1,arr[1],arr[3],arr[4],arr[5]);
	else
		dt = new Date(arr[0],arr[1]-1,arr[2],arr[3],arr[4],arr[5]);

	if(isNaN(dt))
		return null;
//	check date and month
	if(dmy==1 && (dt.getMonth()!=arr[1]-1 || dt.getDate()!=arr[0]))
		return null;
	if(dmy==0 && (dt.getMonth()!=arr[0]-1 || dt.getDate()!=arr[1]))
		return null;
	if(dmy==2 && (dt.getMonth()!=arr[1]-1 || dt.getDate()!=arr[2]))
		return null;
	return dt;
}


function print_datetime(value, format, showtime)
{
//	format: -1 - native (d-m-y)
//			1 - d/m/y
//			0 - m/d/y
//			2 - y/m/d
	var date='';
	if(format==-1)
		date+=(value.getDate()<10?'0'+value.getDate():value.getDate())+'-'+(value.getMonth()<9?'0'+(value.getMonth()+1):value.getMonth()+1)+'-'+value.getFullYear();
	else if(format==1)
		date+=(value.getDate()<10?'0'+value.getDate():value.getDate())+locale_datedelimiter+(value.getMonth()<9?'0'+(value.getMonth()+1):value.getMonth()+1)+locale_datedelimiter+value.getFullYear();
	else if(format==0)
		date+=(value.getMonth()<9?'0'+(value.getMonth()+1):value.getMonth()+1)+locale_datedelimiter+(value.getDate()<10?'0'+value.getDate():value.getDate())+locale_datedelimiter+value.getFullYear();
	else
		date+=value.getFullYear()+locale_datedelimiter+(value.getMonth()<9?'0'+(value.getMonth()+1):value.getMonth()+1)+locale_datedelimiter+(value.getDate()<10?'0'+value.getDate():value.getDate());
	if(!showtime)
		return date;
	var time='';
	if(value.getHours()>0 || value.getMinutes()>0 || value.getSeconds()>0)
	{
		time+=(value.getHours()<10?'0'+value.getHours():value.getHours());
		time+=':'+(value.getMinutes()<10?'0'+value.getMinutes():value.getMinutes())
	}
	if(value.getSeconds()>0)
		time+=':'+(value.getSeconds()<10?'0'+value.getSeconds():value.getSeconds());
	
	return date+' '+time;
}

function SetDate(fldName, recordID)
{
	if ( $('select#month'+fldName+'_'+recordID).get(0).value!='' && $('select#day'+fldName+'_'+recordID).get(0).value!='' && $('select#year'+fldName+'_'+recordID).get(0).value!='') {
		$('input#'+fldName+'_'+recordID).get(0).value = '' + 
			$('select#year'+fldName+'_'+recordID).get(0).value + '-' + 
			$('select#month'+fldName+'_'+recordID).get(0).value + '-' + 
			$('select#day'+fldName+'_'+recordID).get(0).value;
		if ( $('input#ts'+fldName+'_'+recordID)[0] )
			$('input#ts'+fldName+'_'+recordID).get(0).value = '' + 
				$('select#day'+fldName+'_'+recordID).get(0).value + '-' + 
				$('select#month'+fldName+'_'+recordID).get(0).value + '-' + 
				$('select#year'+fldName+'_'+recordID).get(0).value;
	} else {
		if ( $('input#ts'+fldName+'_'+recordID)[0] )
			$('input#ts'+fldName+'_'+recordID).get(0).value= '10-6-2007';
		if ( $('input#'+fldName+'_'+recordID)[0] )
			$('input#'+fldName+'_'+recordID).get(0).value= '';
	}
}


function update(fldName, recordID, newDate, showTime)
{
	var dt_datetime;
	var curdate = new Date();
	dt_datetime = newDate;
	
	if ( $('select#day'+fldName+'_'+recordID)[0] ) {
		$('input#'+fldName+'_'+recordID).get(0).value = dt_datetime.getFullYear() + '-' 
			+ (dt_datetime.getMonth()+1) + '-' + dt_datetime.getDate();
		$('select#day'+fldName+'_'+recordID).get(0).selectedIndex = dt_datetime.getDate();
		$('select#month'+fldName+'_'+recordID).get(0).selectedIndex = dt_datetime.getMonth() + 1;
		for ( i=0; i<$('select#year'+fldName+'_'+recordID).get(0).options.length; i++ ) {
			if ( $('select#year'+fldName+'_'+recordID).get(0).options[i].value == dt_datetime.getFullYear() ) { 
				$('select#year'+fldName+'_'+recordID).get(0).selectedIndex = i; 
				break; 
			} 
			$('input#ts'+fldName+'_'+recordID).get(0).value = dt_datetime.getDate() + '-' + 
				( dt_datetime.getMonth() + 1 ) + '-' + dt_datetime.getFullYear();
		}
	} else {
		$('input#'+fldName+'_'+ recordID).get(0).value = print_datetime(newDate,locale_dateformat,showTime);
		$('input#ts'+fldName+'_'+ recordID).get(0).value = print_datetime(newDate,-1,showTime);
	}
}
