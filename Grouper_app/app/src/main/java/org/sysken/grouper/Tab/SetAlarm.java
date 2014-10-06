package org.sysken.grouper.Tab;

import android.app.Activity;
import android.app.Fragment;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;

import org.sysken.grouper.Alarm.MyAlarmManager;
import org.sysken.grouper.CameraPreviewActivity;
import org.sysken.grouper.GenerateActivity;
import org.sysken.grouper.R;


public class SetAlarm extends Fragment {


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        return inflater.inflate(R.layout.alarm, container, false);
    }

    @Override
    public void onStart() {
        super.onStart();

        Button button1 = (Button)getActivity().findViewById(R.id.group_invite);
        button1.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
               /*
                MyAlarmManager mam = new MyAlarmManager(getActivity().SetAlarm.this);
                mam.addAlarm();
                */
            }
        });

    }
}