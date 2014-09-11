package org.sysken.grouper;

import android.app.Fragment;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.ViewGroup;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Button;
import android.widget.Toast;

public class GroupFragment extends Fragment {

    Globals globals;

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
                Toast.makeText(getActivity(), "hoge!", Toast.LENGTH_SHORT).show();
                Intent mintent = new Intent(getActivity(), GenerateActivity.class);

                // アクティビティの呼び出し
                startActivity(mintent);
            }
        });
        button2.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                Toast.makeText(getActivity(), "hoge!", Toast.LENGTH_SHORT).show();
                Intent mintent = new Intent(getActivity(), CameraPreviewActivity.class);

                // アクティビティの呼び出し
                startActivity(mintent);
            }
        });
    }
}