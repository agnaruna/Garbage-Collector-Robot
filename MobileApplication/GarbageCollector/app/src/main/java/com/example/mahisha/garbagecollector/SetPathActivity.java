package com.example.mahisha.garbagecollector;

import android.app.DatePickerDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.app.TimePickerDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.DatePicker;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.TimePicker;
import android.widget.Toast;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.List;

public class SetPathActivity extends AppCompatActivity implements AdapterView.OnItemSelectedListener {

    private static final int DialogID=0;//  time id
    private static final int DialogIDDate=1; // id of calander
    int hour_x,year_x,month_x,day_x,mint_x;

  private   Spinner spinner; //Dropdown
    TextView txtTime,txtDate;
    String letter; // path variable
    String datetime;

    @Override
    protected void onCreate(Bundle savedInstanceState) { // Just like vb.net formload event
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        txtDate=(TextView)findViewById(R.id.textViewDate);
        txtTime=(TextView)findViewById(R.id.textViewTime);

     spinner=(Spinner)findViewById(R.id.spinner); // bind the spinner
        spinner.setOnItemSelectedListener(this);

        List<String> categories=new ArrayList<String>(); // set all the paths to the array list
        categories.add("A");
        categories.add("B");
        categories.add("C");
        categories.add("D");
        categories.add("E");
        categories.add("F");
        categories.add("G");

        ArrayAdapter<String> pathsadpter =new ArrayAdapter<String>(this,R.layout.support_simple_spinner_dropdown_item,categories);
        pathsadpter.setDropDownViewResource(R.layout.support_simple_spinner_dropdown_item); // describe how to display the data on the spinner
        spinner.setAdapter(pathsadpter);

        final Calendar cal=Calendar.getInstance(); //get the current date to the initial view of the calender picker /datetime picker
        year_x=cal.get(Calendar.YEAR);
        month_x=cal.get(Calendar.MONTH);
        day_x=cal.get(Calendar.DAY_OF_MONTH);

    }

    public void OpenDateCal(View view) {
        showDialog(DialogID);
    } //set dialog id whether it is date or timer picker

    @Override
    protected Dialog onCreateDialog(int id) {//
        if (id==DialogID){ // get the dialog id

            return new TimePickerDialog(this,ontimeset,hour_x,mint_x,false); //show the time picker
        }else if (id==DialogIDDate){
            return new DatePickerDialog(this,onsetDate,year_x,month_x,day_x); //show the date picker
        }// show the current value of date and time comes as default .

        return null;
    }
          //when a date is picked at click ok ;
        DatePickerDialog.OnDateSetListener onsetDate=new DatePickerDialog.OnDateSetListener() {
            @Override
            public void onDateSet(DatePicker view, int year, int month, int dayOfMonth) {
                year_x=year;
                month_x=month+1;// in android months gives as actual month = - one month
                day_x=dayOfMonth;
                txtDate.setText(year_x+":"+month_x+":"+day_x); //set values of above to this text view
            }
        };

    TimePickerDialog.OnTimeSetListener ontimeset=new TimePickerDialog.OnTimeSetListener() {

        @Override
        public void onTimeSet(TimePicker view, int hourOfDay, int minute) {
            hour_x=hourOfDay;
            mint_x=minute;
            txtTime.setText(hour_x+":"+mint_x); //set selected time to text field ,user friendly

        }
    };



    public void OpenTimeCal(View view) {
        showDialog(DialogIDDate);
    } // click event of clock




    public void sendData(View view) {// submit button tasks , error handling
        if (txtDate.getText().equals("Not Set")){
            Toast.makeText(this,"Please select the date.",Toast.LENGTH_LONG).show(); // error handling when a date and a time is not selected

        }else if (txtTime.getText().equals("Not Set")) {
            Toast.makeText(this, "Please select the time.", Toast.LENGTH_LONG).show();

        }else if (iswebserverknow()){
            datetime=(year_x+"-"+month_x+"-"+day_x+" "+hour_x+":"+mint_x); //set  date time
            savePathTask spt=new savePathTask(this); // create object from save path task (save path task is extended async task which is a thread
            spt.execute(); // execute the async task ,save the all data

        }
        else {
            AlertDialog.Builder errbuilt=new AlertDialog.Builder(this); // error handling message box popsup if the web server address in not available
            errbuilt.setIcon(R.drawable.weit)
                    .setTitle("Wait..")
                    .setMessage("There is no web server address")
                    .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            startActivity(new Intent(getBaseContext(),actSettings.class));
                        }
                    })
                    .setNegativeButton("No", new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            dialog.cancel();
                        }
                    });
            AlertDialog altmsg=errbuilt.create();
            altmsg.show(); // show the message


        }

    }

    @Override //save the selected path ex:a b c
    public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {// when selecting drop down to put the selected value
    letter= parent.getItemAtPosition(position).toString().trim();
        Toast.makeText(this,letter,Toast.LENGTH_LONG).show(); // show the inserted value in black
    }

    @Override
    public void onNothingSelected(AdapterView<?> parent) {

    }
    //implementing code for navigation bar

    public void openHomemain(View view) {
        startActivity(new Intent(this,MainActivity.class));
    }

    public void Opentasklist(View view) {
        startActivity(new Intent(this,TasksActivity.class));
    }

    public void opensettigs(View view) {
        startActivity(new Intent(this,actSettings.class));
    }

    public void OpenHelp(View view) {
        startActivity(new Intent(this,activityHelp.class));
    }

    public boolean iswebserverknow(){// check for url
        SharedPreferences setref=getSharedPreferences("settings",MODE_PRIVATE);
        String url=setref.getString("url","");
        if (url.isEmpty()){
            return false;
        }
        else {
            return true;
        }
    }

    class  savePathTask extends AsyncTask<Void,Void,String> { //  the async class is extend and output a string parameter  ,theading

        ProgressDialog progressDialog;
        String URL_insert;
        Context context;

        public savePathTask(Context context) {
            this.context = context;
        }



        @Override
        protected void onPreExecute() {// first step executing save path

           progressDialog=new ProgressDialog(context); // prgress of the task
            progressDialog.setTitle("Please wait..!");
            progressDialog.setIcon(R.drawable.stophand);
            progressDialog.setProgressStyle(ProgressDialog.STYLE_SPINNER);
            progressDialog.setMessage("Data is being Saved..!");
            progressDialog.show();
            SharedPreferences setref=getSharedPreferences("settings",MODE_PRIVATE);
            String url=setref.getString("url","");
            URL_insert="http://"+url+"/insertTK.php"; // insert url with access to php page and assign it back to url_insert as a single string

        }

        @Override
        protected void onPostExecute(String msg) {// 3rd of async task /save path class
         progressDialog.cancel();// progress dialog stops now
            Toast.makeText(getApplicationContext(),msg,Toast.LENGTH_LONG).show(); // msg = message from the server (error or success)
            clearData();
        }



        @Override
        protected String doInBackground(Void... params)  {// second step of asyn task/save pathclass

            try {

            URL url=new URL(URL_insert); // send the string of above to url
            HttpURLConnection httpURLConnection=(HttpURLConnection) url.openConnection(); // create http connection with url
            httpURLConnection.setRequestMethod("POST"); // send data with post method , post method is not visible as get method
            httpURLConnection.setDoOutput(true); // output the send data
            OutputStream outputStream=httpURLConnection.getOutputStream(); // create a output stream to send data using http connection
            BufferedWriter bufferedWriter=new BufferedWriter(new OutputStreamWriter(outputStream,"UTF-8")); // use bufferedwriter to write script to a character output stream
            String dataString= URLEncoder.encode("path","UTF-8")+"="+URLEncoder.encode(letter.trim(),"UTF-8")+"&"+ // data block name = path ,utf = Unicode Transformation Format  ,utf -8 =type,
                    URLEncoder.encode("date_time","UTF-8")+"="+URLEncoder.encode(datetime,"UTF-8");//

            bufferedWriter.write(dataString); // whole string insert to buffered writer
            bufferedWriter.flush(); // stop writting
            InputStream inputStream= httpURLConnection.getInputStream(); // to read the msg from the server
            BufferedReader bufferedReader=new BufferedReader(new InputStreamReader(inputStream,"iso-8859-1"));
            String respons="";
            String line="";
            while ((line=bufferedReader.readLine())!=null){
                respons+=line; // put value to response
            }
            bufferedReader.close();
            inputStream.close();
            bufferedWriter.close();
            outputStream.close();
            httpURLConnection.disconnect();
            return respons;
            }catch (IOException ex){

            }
            return null;
        }
    }

  private void clearData(){//  clear data
      final Calendar cal=Calendar.getInstance();
      year_x=cal.get(Calendar.YEAR);
      month_x=cal.get(Calendar.MONTH);
      day_x=cal.get(Calendar.DAY_OF_MONTH);
      hour_x=0; // get it back to default
      mint_x=0;
      datetime=null;
      letter=null;
      txtDate.setText(null);
      txtTime.setText(null);
  }

}
