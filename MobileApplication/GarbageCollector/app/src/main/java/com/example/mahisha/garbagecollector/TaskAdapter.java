package com.example.mahisha.garbagecollector;

import android.content.Context;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import java.util.ArrayList;
import java.util.List;



public class TaskAdapter extends ArrayAdapter {
    List list=new ArrayList();

    public TaskAdapter(Context context, int resource) {
        super(context, resource);
    }


    public void add(Task object) {
        super.add(object);
        list.add(object);
    }

    @Override
    public int getCount() {
        return list.size();
    }

    @Override
    public void clear() {
        list.clear();
    }

    @Nullable
    @Override
    public Object getItem(int position) {
        return list.get(position);
    }

    @NonNull
    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        View row;
        Taskholder taskholder;

        row=convertView;
        if (row==null){
            LayoutInflater layoutInflater= (LayoutInflater) this.getContext().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
            row=layoutInflater.inflate(R.layout.row_layout,parent,false);
            taskholder=new Taskholder();
            taskholder.tx_path=(TextView)row.findViewById(R.id.txtpathname); // put data according to row layout
            taskholder.tx_date=(TextView)row.findViewById(R.id.txtDatetime);
            taskholder.tx_status=(TextView)row.findViewById(R.id.txtstatus);
            row.setTag(taskholder);
        }else {
            taskholder=(Taskholder) row.getTag();
        }

        Task task= (Task) this.getItem(position);
        taskholder.tx_path.setText(task.getPath());
        taskholder.tx_date.setText(task.getDate());
        taskholder.tx_status.setText(task.getStatus());

        return row;
    }


    static class Taskholder {
        TextView tx_path,tx_date,tx_status;

    }


}
