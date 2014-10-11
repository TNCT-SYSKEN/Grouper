package org.sysken.grouper.Tab;

import android.app.Fragment;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.ViewGroup;
import android.view.View;

import org.sysken.grouper.Globals;
import org.sysken.grouper.R;

public class Disaster extends Fragment {

    Globals globals;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        return inflater.inflate(R.layout.disaster, container, false);
    }

    @Override
    public void onStart() {
        super.onStart();




    }
}