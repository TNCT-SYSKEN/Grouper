package org.sysken.grouper;


import android.app.ActionBar;
import android.app.Activity;
import android.app.Fragment;
import android.app.FragmentManager;
import android.app.FragmentTransaction;
import android.os.Bundle;
import android.view.Menu;


public class TabAct extends Activity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.tab);

        //アクションバーのセットアップ
        final ActionBar actionBar = getActionBar();
        actionBar.setNavigationMode(ActionBar.NAVIGATION_MODE_TABS);

        actionBar.addTab(actionBar
                .newTab()
                .setText("ページ1")
                .setTabListener(
                        new TabListener<HomeFragment>(
                                this, "tag1", HomeFragment.class)
                ));

        actionBar.addTab(actionBar.newTab()
                .setText("ページ2")
                .setTabListener(
                        new TabListener<GroupFragment>(
                                this, "tag2", GroupFragment.class)
                ));
        actionBar.addTab(actionBar.newTab()
                .setText("ページ3")
                .setTabListener(
                        new TabListener<Setting>(
                                this, "tag3", Setting.class)
                ));
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.main, menu);
        return true;
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


