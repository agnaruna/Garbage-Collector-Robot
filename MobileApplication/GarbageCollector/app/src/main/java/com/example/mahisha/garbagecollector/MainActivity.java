package com.example.mahisha.garbagecollector;

import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.ConnectivityManager;
import android.os.Bundle;
import android.provider.Settings;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.view.View;

public class MainActivity extends AppCompatActivity {



    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_mainv);


    }

    public void startsetpath(View view) {
        if (isNetworkAvailable()) {
            startActivity(new Intent(this, SetPathActivity.class));
        }else {
            AlertDialog.Builder altmsg=new AlertDialog.Builder(this);
            altmsg.setTitle("Information")
                    .setIcon(R.drawable.stophand)
                    .setMessage("Please connect to the Internet..!")
                    .setCancelable(false)
                    .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            startActivity(new Intent(Settings.ACTION_DATA_ROAMING_SETTINGS));
                            finish();
                        }
                    })
                    .setNegativeButton("No", new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            dialog.cancel();
                        }
                    });
            AlertDialog alertDialog=altmsg.create();
            alertDialog.show();
        }
    }

    /*
    Calendar cal = new GregorianCalendar(2013, 5, 0);
    Date date = cal.getTime();
    DateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");
    System.out.println("Date : " + sdf.format(date));

*/

    public void Opentaskact(View view) {
        if (isNetworkAvailable()&& iswebserverknow()) {
            startActivity(new Intent(this, TasksActivity.class));

        }else if (!iswebserverknow()){
            AlertDialog.Builder errbuilt=new AlertDialog.Builder(this);
            errbuilt.setIcon(R.drawable.weit)
                    .setTitle("Wait..")
                    .setMessage("There is no web server address. Please Change the settings")
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
            altmsg.show();

        } else {
            AlertDialog.Builder altmsg=new AlertDialog.Builder(this);
            altmsg.setTitle("Information")
                    .setIcon(R.drawable.stophand)
                    .setMessage("Please connect to the Internet..!")
                    .setCancelable(false)
                    .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            startActivity(new Intent(Settings.ACTION_DATA_ROAMING_SETTINGS));
                            finish();
                        }
                    })
                    .setNegativeButton("No", new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            dialog.cancel();
                        }
                    });
            AlertDialog alertDialog=altmsg.create();
            alertDialog.show();
        }
    }


    public void OpenSettings(View view) {
        startActivity(new Intent(this,actSettings.class));
    }


    public boolean isNetworkAvailable() {
        final ConnectivityManager connectivityManager = ((ConnectivityManager) getSystemService(CONNECTIVITY_SERVICE));
        return connectivityManager.getActiveNetworkInfo() != null && connectivityManager.getActiveNetworkInfo().isConnected();
    }


    public void openhelp(View view) {
        startActivity(new Intent(this,activityHelp.class));
    }

    public boolean iswebserverknow(){
        SharedPreferences setref=getSharedPreferences("settings",MODE_PRIVATE);
        String url=setref.getString("url","");
        if (url.isEmpty()){
            return false;
        }
        else {
            return true;
        }
    }



}

