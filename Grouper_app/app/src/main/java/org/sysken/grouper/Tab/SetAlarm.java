package org.sysken.grouper.Tab;


import android.app.AlarmManager;
import android.app.Fragment;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.content.res.Resources;
import android.os.Bundle;
import android.text.format.Time;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.NumberPicker;
import android.widget.TimePicker;
import android.widget.Toast;

import org.sysken.grouper.Alarm.AlarmReceiver;
import org.sysken.grouper.Alarm.MyAlarmManager;
import org.sysken.grouper.R;

import java.util.Calendar;


public class SetAlarm extends Fragment {

    public NumberPicker numberPicker1;
    public NumberPicker numberPicker2;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        View v = inflater.inflate(R.layout.alarm, container, false);


        return v;
    }

    @Override
    public void onStart() {

        super.onStart();


        Time time = new Time("Asia/Tokyo");

        numberPicker1 = (NumberPicker)getActivity().findViewById(R.id.number_picker1);
        numberPicker2 = (NumberPicker)getActivity().findViewById(R.id.number_picker2);

        numberPicker1.setMaxValue(23);
        numberPicker1.setMinValue(0);

        numberPicker2.setMaxValue(59);
        numberPicker2.setMinValue(0);

        numberPicker1.setValue(time.hour);
        numberPicker2.setValue(time.minute);

        Button btn = (Button)getActivity().findViewById(R.id.start);
        btn.setOnClickListener(new View.OnClickListener(){

            @Override
            public void onClick(View v){
                MyAlarmManager mam = new MyAlarmManager(getActivity());
                mam.addAlarm(numberPicker1.getValue(),numberPicker2.getValue());
            }

        });


    }
}