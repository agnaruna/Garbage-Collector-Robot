package com.example.mahisha.garbagecollector;

import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

public class actSettings extends AppCompatActivity {

    private EditText txturl;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_act_settings);

        txturl=(EditText)findViewById(R.id.editTextURL);
        SharedPreferences sharedPreferences=getSharedPreferences("settings",MODE_PRIVATE);
        String Url=sharedPreferences.getString("url","");
        if (!Url.isEmpty()){
            txturl.setText(Url);
        }

    }

    public void saveURL(View view) {
        SharedPreferences sharedPrefe=getSharedPreferences("settings",MODE_PRIVATE);
        SharedPreferences.Editor editor=sharedPrefe.edit();
        editor.putString("url",txturl.getText().toString());
        editor.apply();
        Toast.makeText(this,"Settings is being saved..!",Toast.LENGTH_LONG).show();
    }
}
