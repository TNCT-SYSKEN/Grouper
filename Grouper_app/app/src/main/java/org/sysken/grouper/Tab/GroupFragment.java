package org.sysken.grouper.Tab;

import android.app.Fragment;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.ViewGroup;
import android.view.View;
import android.widget.Button;

import org.sysken.grouper.CameraPreviewActivity;
import org.sysken.grouper.GenerateActivity;
import org.sysken.grouper.R;

public class GroupFragment extends Fragment {


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        return inflater.inflate(R.layout.group, container, false);
    }

    @Override
    public void onStart() {
        super.onStart();

        Button button1 = (Button)getActivity().findViewById(R.id.group_invite);
        Button button2 = (Button)getActivity().findViewById(R.id.group_search);
        button1.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                Intent mintent = new Intent(getActivity(), GenerateActivity.class);

                // アクティビティの呼び出し
                startActivity(mintent);
            }
        });
        button2.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                Intent mintent = new Intent(getActivity(), CameraPreviewActivity.class);

                // アクティビティの呼び出し
                startActivity(mintent);
            }
        });
    }
}