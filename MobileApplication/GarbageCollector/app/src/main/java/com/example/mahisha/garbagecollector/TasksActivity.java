package com.example.mahisha.garbagecollector;

import android.animation.LayoutTransition;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.design.widget.FloatingActionButton;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.ActionMode;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.NumberPicker;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Spinner;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

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
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.GregorianCalendar;
import java.util.List;

public class TasksActivity extends AppCompatActivity implements AbsListView.MultiChoiceModeListener,RadioGroup.OnCheckedChangeListener{

    TaskAdapter taskAdapter; // to create connection between server and list view
    JSONObject jsonObject;
    JSONArray jsonArray;

    int count=0;
    List selections =new ArrayList();
    String JSON_String;
    ListView listView;
    RadioGroup radioGroup;
    Spinner spinner;
    NumberPicker npyear,npmonth;
    FloatingActionButton fab;
    ViewGroup viewGroupnu;

    String databag=null;
    String json_get_key="all";

    String[] status={"not yet","pending","complete"};
    String[] months={"JANUARY","FEBRUARY","MARCH","APRIL","MAY","JUNE","JULY","AUGUST","SEPTEMBER","OCTOBER","NOVEMBER","DECEMBER"};
    private final String TAG="com.example.mahisha";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_tasks);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
       setSupportActionBar(toolbar);
        Calendar cal=Calendar.getInstance();

        ViewGroup viewGroupMain=(ViewGroup)findViewById(R.id.content_tasks);
        viewGroupMain.setLayoutTransition(new LayoutTransition());

        npmonth=(NumberPicker)findViewById(R.id.Numpickermonth);
        npmonth.setDisplayedValues(months);
        npmonth.setMinValue(1);
        npmonth.setMaxValue(12);
        npmonth.setValue(cal.get(Calendar.MONTH));



        npyear=(NumberPicker)findViewById(R.id.Numpickeryear);
        npyear.setMinValue(1995);
        npyear.setMaxValue(2050);
        npyear.setValue(cal.get(Calendar.YEAR));

        radioGroup=(RadioGroup)findViewById(R.id.rtbgroup);
        radioGroup.setOnCheckedChangeListener(this);

        //bind the task adpater with row layout

        taskAdapter=new TaskAdapter(this,R.layout.row_layout); // bind the rowlayout to task adpater
        listView=(ListView)findViewById(R.id.taskslist);
        listView.setAdapter(taskAdapter); //set adpater to list view
        listView.setChoiceMode(AbsListView.CHOICE_MODE_MULTIPLE_MODAL); // allow to select multiple on list view ex :to delete
        listView.setMultiChoiceModeListener(this); // multiple choic lister create


        spinner=(Spinner)findViewById(R.id.spinnerstatus);
        ArrayAdapter arrayAdapter=new ArrayAdapter(this, R.layout.support_simple_spinner_dropdown_item,status);
        spinner.setAdapter(arrayAdapter);

        // floating button action click
       fab = (FloatingActionButton) findViewById(R.id.fab);
        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
               funtiontoolHandle();
            }
        });

        tasktotask task=new tasktotask(this);
        task.execute();


    }

    //  viewgroup means task list
    private void funtiontoolHandle(){
        ViewGroup viewGroup=(ViewGroup)findViewById(R.id.funtionlayout);
        ViewGroup viewGrouplist=(ViewGroup)findViewById(R.id.listviewlayout);
        ViewGroup viewGrouptitl=(ViewGroup)findViewById(R.id.layouttitle);

        if (viewGroup.getVisibility()==View.GONE) {
            viewGrouplist.setVisibility(View.GONE);
            viewGrouptitl.setVisibility(View.GONE);
            viewGroup.setVisibility(View.VISIBLE);
            fab.setImageResource(R.drawable.ic_close_black_24dp); // change the icon to x

        }else {
            viewGroup.setVisibility(View.GONE);
            viewGrouptitl.setVisibility(View.VISIBLE);
            viewGrouplist.setVisibility(View.VISIBLE);
            fab.setImageResource(R.drawable.ic_menu_black_24dp); //change the icon to --

        }
    }


    @Override  // when selecting item from list
    public void onItemCheckedStateChanged(ActionMode mode, int position, long id, boolean checked) {
     if (checked){
        selections.add(taskAdapter.getItem(position)); // keep separete list as "selction" and add to it
         count++; // keep the count of selected items
         mode.setTitle(count+" Selected");
     }else {
         selections.remove(taskAdapter.getItem(position)); // remove selected item /deselet it again
         count--; // reduce count
         mode.setTitle(count+" Selected"); //display tittle
     }

    }

    @Override // show the delete icon which was created in menu_view_task
    public boolean onCreateActionMode(ActionMode mode, Menu menu) {

        MenuInflater menuInflater=getMenuInflater();
        menuInflater.inflate(R.menu.menu_view_tasks,menu);
        return true;
    }

     // not used
    @Override
    public boolean onPrepareActionMode(ActionMode mode, Menu menu) {
        return false;
    }


    //  actions performed when button click is select to delete the tasks
    @Override
    public boolean onActionItemClicked(final ActionMode mode, MenuItem item) {
        if (item.getItemId()==R.id.id_delete){

            AlertDialog.Builder alt=new AlertDialog.Builder(this);
            alt.setTitle("Please Weit..")
                    .setMessage("Are you sure..! Do you want to delete ? ") // validation  msg
                    .setCancelable(false)
                    .setIcon(R.drawable.weit)
                    .setNegativeButton("NO", new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            dialog.cancel();
                        }
                    })
                    .setPositiveButton("Yes", new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {

                            for (int a=0;a<selections.size();a++){ // get the selected items from  'slection " array list
                                 Task newtask=(Task)selections.get(a);
                                if (databag==null){
                                    databag=newtask.getRoundid()+":";
                                }else {
                                    databag +=newtask.getRoundid()+":";
                                }
                            }
                            setstart(mode); // call to setsatart mode to delete multiple

                        }
                    });
            AlertDialog alertDialog=alt.create();
            alertDialog.show(); //visible the  validation msg to confirm delete or not

        }

        return true;
    }

    @Override
    public void onDestroyActionMode(ActionMode mode) {
    selections.clear();
        count=0;
    }

    // delete the selected values through the async task
    private void setstart(ActionMode mode){
        deletetask dt=new deletetask(this,databag);
        dt.execute();
        refill(); // create the list view again
        mode.finish(); // remove from the mode /remove bar
    }

    // call the refill function and do the task refill after delete
    private  void refill(){
        taskAdapter.clear();
        tasktotask ttt=new tasktotask(this);
        ttt.execute();
        taskAdapter.notifyDataSetChanged();

    }

    // radio button check , visbility change according to selceted rb
    @Override
    public void onCheckedChanged(RadioGroup group, int checkedId) {

        ViewGroup viewGroupradiolayout=(ViewGroup)findViewById(R.id.radiolayout);
        viewGroupradiolayout.setLayoutTransition(new LayoutTransition());
        RadioButton radioButton;
        radioButton=(RadioButton)findViewById(checkedId);
        viewGroupnu=(ViewGroup)findViewById(R.id.Numpickerlayout);

        if (radioButton.getId()==R.id.rtbstats){
            spinner.setVisibility(View.VISIBLE);
            viewGroupnu.setVisibility(View.GONE);
        }else if (radioButton.getId()==R.id.rtball){
            viewGroupnu.setVisibility(View.GONE);
            spinner.setVisibility(View.GONE);
        }else {
            viewGroupnu.setVisibility(View.VISIBLE);
            spinner.setVisibility(View.GONE);
        }



    }

     // load button event
    public void Reloadlistview(View view) {

        if (radioGroup.getCheckedRadioButtonId()==R.id.rtbstats){// check wheter rb clicked is stsuts
            String status=spinner.getSelectedItem().toString();

            json_get_key="status"+","+status; // create json string of status to send to server ,hence server will send only that data :ex:nt yet
            refill();

        }else if (radioGroup.getCheckedRadioButtonId()==R.id.rtbdate){ // check wheter rb is for calander
            Calendar cal = new GregorianCalendar(npyear.getValue(), npmonth.getValue(), 0); // create the final date of the month and year / to get a range
            Date date = cal.getTime();
            DateFormat sdf = new SimpleDateFormat("yyyy-MM-dd"); //create the details to this date format
            json_get_key="datetime"+","+sdf.format(date); // send data of that format to json key
            refill(); // send that key to server

        }else {
            json_get_key="all"; // if seletc view all ,get data frm server
            refill();
        }

        funtiontoolHandle();

    }
    //navigation bar

    public void OpenHomeAct(View view) {
        startActivity(new Intent(this,MainActivity.class));
    }

    public void Openpathact(View view) {
        startActivity(new Intent(this,SetPathActivity.class));
    }



    public void opensettinwin(View view) {
        startActivity(new Intent(this,actSettings.class));
    }


    class  deletetask extends AsyncTask<Void,Void,String>{
      Context context;
        ProgressDialog progressDialog;
        String URL_delete;
        String delete_val;


        public deletetask(Context context,String delete_val) {
            this.context = context;
            this.delete_val=delete_val;
        }

        @Override
        protected void onPreExecute() {
            progressDialog=new ProgressDialog(context);
            progressDialog.setTitle("Please wait..!");
            progressDialog.setIcon(R.drawable.stophand);
            progressDialog.setProgressStyle(ProgressDialog.STYLE_SPINNER);
            progressDialog.setMessage("Data is being deleted..!");
            progressDialog.show();
            SharedPreferences setref=getSharedPreferences("settings",MODE_PRIVATE);
            String url=setref.getString("url","");
            URL_delete="http://"+url+"/del_json_data.php"; // call to delete json data php
        }

        @Override
        protected void onPostExecute(String result) {
            selections.clear();
            Toast.makeText(context,result,Toast.LENGTH_LONG).show(); // show the msg from server
            progressDialog.cancel();
        }

        @Override
        protected void onProgressUpdate(Void... values) {
            super.onProgressUpdate(values);
        }

        // communicate with server
        @Override
        protected String doInBackground(Void... params) {
            try {

                URL url = new URL(URL_delete); // path of php page that include items to delete
                HttpURLConnection httpURLConnection = (HttpURLConnection) url.openConnection();
                httpURLConnection.setRequestMethod("POST");
                httpURLConnection.setDoOutput(true);
                OutputStream outputStream = httpURLConnection.getOutputStream();
                BufferedWriter bufferedWriter = new BufferedWriter(new OutputStreamWriter(outputStream, "UTF-8"));
                String dataString = URLEncoder.encode("rid", "UTF-8") + "=" + URLEncoder.encode(delete_val, "UTF-8") ;

                bufferedWriter.write(dataString);
                bufferedWriter.flush();
                InputStream inputStream = httpURLConnection.getInputStream();
                BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(inputStream, "iso-8859-1"));
                String respons = "";
                String line = "";
                while ((line = bufferedReader.readLine()) != null) {
                    respons += line;
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

//load data
    class tasktotask extends AsyncTask<Void, Void, String> {

        private ProgressDialog progressDialog;
        Context context;
        String URL_Json_data;

        public tasktotask(Context context) {
            this.context = context;
        }

        @Override
        protected void onPreExecute() {
            SharedPreferences setref=getSharedPreferences("settings",MODE_PRIVATE);
            String url=setref.getString("url","");
            URL_Json_data="http://"+url+"/Json_get_Data.php";

            progressDialog=new ProgressDialog(context);
            progressDialog.setTitle("Please wait...!");
            progressDialog.setMessage("Data is being accessed... ");
            progressDialog.setProgressStyle(ProgressDialog.STYLE_SPINNER);
            progressDialog.setIcon(R.drawable.stophand);
            progressDialog.show();
        }

        @Override // JSON communication to load data/list view
        protected void onPostExecute(String result) {

            JSON_String=result;

            try {
                jsonObject=new JSONObject(JSON_String);
                jsonArray=jsonObject.getJSONArray("server_response");

                int count=0;
                int id;
                String path,date,status;

                while (count<jsonArray.length()){

                    JSONObject jo=jsonArray.getJSONObject(count);
                    id=Integer.parseInt(jo.getString("roundid"));
                    path=jo.getString("path_name");
                    date=jo.getString("Date_Time");
                    status=jo.getString("RB_status");

                    Task task=new Task(path,id,date,status);
                        taskAdapter.add(task);
                        count++;
                }


            } catch (JSONException e) {
                e.printStackTrace();
            }
            progressDialog.cancel();
        }

        @Override
        protected void onProgressUpdate(Void... values) {

        }

        @Override
        protected String doInBackground(Void... params) {



            try {
            URL url=new URL(URL_Json_data);
            HttpURLConnection httpURLConnection=(HttpURLConnection) url.openConnection();

                httpURLConnection.setRequestMethod("POST");
                httpURLConnection.setDoOutput(true);
                OutputStream outputStream=httpURLConnection.getOutputStream();
                BufferedWriter bufferedWriter=new BufferedWriter(new OutputStreamWriter(outputStream,"UTF-8"));
                String dataString= URLEncoder.encode("key","UTF-8")+"="+URLEncoder.encode(json_get_key,"UTF-8");

                bufferedWriter.write(dataString);
                bufferedWriter.flush();

            InputStream inputStream= httpURLConnection.getInputStream();
            BufferedReader bufferedReader=new BufferedReader(new InputStreamReader(inputStream,"iso-8859-1"));
            String respons="";
            String line="";
            while ((line=bufferedReader.readLine())!=null){
                respons+=line+"\n";
            }
                bufferedWriter.close();
                outputStream.close();
            bufferedReader.close();
                httpURLConnection.disconnect();
                Log.i(TAG,"Task doin end");
                inputStream.close();
                return respons;
            } catch (IOException e) {
                e.printStackTrace();
            }


            return null;
        }
    }




}
