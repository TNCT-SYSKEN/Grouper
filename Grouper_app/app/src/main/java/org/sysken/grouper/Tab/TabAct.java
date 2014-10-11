package org.sysken.grouper.Tab;


import android.app.ActionBar;
import android.app.Activity;
import android.app.Fragment;
import android.app.FragmentManager;
import android.app.FragmentTransaction;
import android.app.NotificationManager;
import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;

import com.google.android.gms.gcm.GoogleCloudMessaging;

import org.sysken.grouper.CameraPreviewActivity;
import org.sysken.grouper.GenerateActivity;
import org.sysken.grouper.Globals;
import org.sysken.grouper.R;

import java.io.IOException;


public class TabAct extends Activity {
    Globals globals;
    public static final String PREFERENCES_FILE_NAME = "preference";
    private GoogleCloudMessaging gcm;
    private Context context;
    Fragment homeFragment = new HomeFragment();


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.tab);

        int number = 571034875;
        NotificationManager notificationManager = (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
        notificationManager.cancel(number);

        context = getApplicationContext();
        globals = (Globals) this.getApplication();

        registerInBackground();

        //アクションバーのセットアップ
        final ActionBar actionBar = getActionBar();
        actionBar.setNavigationMode(ActionBar.NAVIGATION_MODE_TABS);

        actionBar.addTab(actionBar
                .newTab()
                .setText(R.string.group)
                .setTabListener(
                        new TabListener<HomeFragment>(
                                this, "tag1", HomeFragment.class)
                ));

        actionBar.addTab(actionBar.newTab()
                .setText(R.string.talk)
                .setTabListener(
                        new TabListener<GroupFragment>(
                                this, "tag2", GroupFragment.class)
                ));
        actionBar.addTab(actionBar.newTab()
                .setText(R.string.disaster)
                .setTabListener(
                        new TabListener<Disaster>(
                                this, "tag3", Disaster.class)
                ));
        actionBar.addTab(actionBar.newTab()
                .setText(R.string.alarm)
                .setTabListener(
                        new TabListener<SetAlarm>(
                                this, "tag4", SetAlarm.class)
                ));
    }


    private void registerInBackground() {
        new AsyncTask<Void, Void, String>() {
            @Override
            protected String doInBackground(Void... params) {
                String msg = "";
                try {
                    if (gcm == null)
                        gcm = GoogleCloudMessaging.getInstance(context);
                    msg = gcm.register("903569435747");
                    globals.registrationId = msg;
                } catch (IOException ex) {
                    msg = "Error :" + ex.getMessage();
                }
                return msg;
            }
        }.execute();
    }

    @Override
    public void onBackPressed() {
        Fragment webView = getFragmentManager().findFragmentById(R.id.container);
        if (webView instanceof HomeFragment) {
            boolean goBack = ((HomeFragment)webView).canGoBack();
            if (!goBack) {
                super.onBackPressed();
            }else{
                ((HomeFragment) webView).GoBack();
            }
        }
        if(webView instanceof GroupFragment){
            boolean goBack = ((GroupFragment)webView).canGoBack();
            if(!goBack){
                super.onBackPressed();
            }else{
                ((GroupFragment)webView).GoBack();
            }
        }
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.my, menu);

        return true;
    }

    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case R.id.QRR:

                // 明示的なインテントの生成
                Intent intent = new Intent(this, CameraPreviewActivity.class);

                // アクティビティの呼び出し
                startActivity(intent);

                return true;

            case R.id.QRW:

                // 明示的なインテントの生成
                Intent mintent = new Intent(this, GenerateActivity.class);
                // アクティビティの呼び出し
                startActivity(mintent);

                return true;
            case R.id.Setting:

                // 明示的なインテントの生成
                Intent intent2 = new Intent(this, Setting.class);
                // アクティビティの呼び出し
                startActivity(intent2);

                return true;
        }
        return false;
    }

    public class TabListener<T extends Fragment> implements ActionBar.TabListener {
        private Fragment mFragment;
        private final Activity mActivity;
        private final String mTag;
        private final Class<T> mClass;

        public Fragment getFragment() {
            return mFragment;
        }
        public TabListener(Activity activity, String tag, Class<T> clz) {
            mActivity = activity;
            mTag = tag;
            mClass = clz;
        }



        @Override
        public void onTabSelected(ActionBar.Tab tab, FragmentTransaction ft) {
            //ftはnullではないが使用するとNullPointExceptionで落ちる
            if (mFragment == null) {
                mFragment = Fragment.instantiate(mActivity, mClass.getName());
                FragmentManager fm = mActivity.getFragmentManager();
                fm.beginTransaction().add(R.id.container, mFragment, mTag).commit();
            } else {
                //detachされていないときだけattachするよう変更
                if (mFragment.isDetached()) {
                    FragmentManager fm = mActivity.getFragmentManager();
                    fm.beginTransaction().attach(mFragment).commit();
                }
            }
        }

        @Override
        public void onTabUnselected(ActionBar.Tab tab, FragmentTransaction ft) {
            if (mFragment != null) {
                FragmentManager fm = mActivity.getFragmentManager();
                fm.beginTransaction().detach(mFragment).commit();
            }
        }

        @Override
        public void onTabReselected(ActionBar.Tab tab, FragmentTransaction ft){

        }


    }
}


