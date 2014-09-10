package org.sysken.grouper;


import android.app.ActionBar;
import android.app.Activity;
import android.app.Fragment;
import android.app.FragmentManager;
import android.app.FragmentTransaction;
import android.content.Intent;
import android.graphics.Camera;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;


public class TabAct extends Activity {
    public static final String PREFERENCES_FILE_NAME = "preference";
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.tab);

        //アクションバーのセットアップ
        final ActionBar actionBar = getActionBar();
        actionBar.setNavigationMode(ActionBar.NAVIGATION_MODE_TABS);

        actionBar.addTab(actionBar
                .newTab()
                .setText(R.string.home)
                .setTabListener(
                        new TabListener<HomeFragment>(
                                this, "tag1", HomeFragment.class)
                ));

        actionBar.addTab(actionBar.newTab()
                .setText(R.string.group)
                .setTabListener(
                        new TabListener<GroupFragment>(
                                this, "tag2", GroupFragment.class)
                ));
        actionBar.addTab(actionBar.newTab()
                .setText(R.string.settings)
                .setTabListener(
                        new TabListener<Setting>(
                                this, "tag3", Setting.class)
                ));
        /*
        actionBar.addTab(actionBar.newTab()
                .setText(R.string.QRR)
                .setTabListener(
                        new TabListener<CameraPreviewActivity>(
                                this, "tag4", CameraPreviewActivity.class)
                ));
        */
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

        }
        return false;
    }

    public class TabListener<T extends Fragment> implements ActionBar.TabListener {
        private Fragment mFragment;
        private final Activity mActivity;
        private final String mTag;
        private final Class<T> mClass;

        public TabListener(Activity activity, String tag, Class<T> clz) {
            mActivity = activity;
            mTag = tag;
            mClass = clz;
        }


        @Override
        public void onTabSelected(ActionBar.Tab tab, FragmentTransaction ft) {
            //ftはnullではないが使用するとぬるぽで落ちる
            if (mFragment == null) {
                mFragment = Fragment.instantiate(mActivity, mClass.getName());
                ft.add(android.R.id.content, mFragment, mTag);
            } else {
                ft.attach(mFragment);
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
        public void onTabReselected(ActionBar.Tab tab, FragmentTransaction ft) {
        }
    }
}


