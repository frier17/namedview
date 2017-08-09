<?php
namespace App\NamedView;

use App\Http\Controllers\Controller;
use App\User;
use \Illuminate\Http\Request;

/**
 * Description of NamedView
 * Define functions for the named view for rendering template pages using controller function name
 *
 * @author frier17 (a17s)
 */
class NamedView {
    
    /**
     * Holds a mapping of function name to view file to be rendered for that function.
     * The format of the array is:
     * [ 'methodName' => 'view.file' ]
     * 
     * @var type array
     */
    protected $views = [];

    const VS_KEY_DATA = 3;
    const VS_KEY_MESSAGE = 4;
    const VS_KEY_VIEW = 5;
    const VS_KEY_IDENTITY = 6;
    // view data key for model instance and lists
    const DT_KEY_OBJECTS = 7;
    const DT_KEY_INSTANCE = 8;
    // constants to define the identity to present information to
    const ID_KEY_ADMIN = 9;
    const ID_KEY_USER = 10;
    const ID_KEY_RESOURCE = 11;
    // constants to define messaging dialogs
    const MSG_KEY_OK = 12;
    const MSG_KEY_CANCEL = 13;
    const MSG_KEY_ABORT = 14;
    const MSG_KEY_RETRY = 15;
    const MSG_KEY_IGNORE = 16;
    const MSG_KEY_YES = 17;
    const MSG_KEY_NO = 18;
    // constants to define the preference or style options for usrs
    const ID_PREF_OPTION = 19;
    const ID_OPT_ADMIN = 20;
    const ID_OPT_DEFAULT = 21;
    const ID_OPT_OWNER = 22;



    // assign the view file from the controller to the named view
    /**
     * Assign a given controller function to a view file. The controller function is specified as Controller@function.
     * @param type $tags
     * @param type $view
     * @param type $identity specify the user|resource|model for which the view is being rendered
     * @param array $data pass information or data to be rendered in the final view
     * @param array $message pass flash message or user feedback message to the view to be rendered
     * @throws Exception
     * @return The current NamedView instance
     */
    public function map($tags, $view = null, $identity = null, $data = [], $message = [])
    {
        // parse the $tag variable to its respective types
        $names = [];

        if(is_array($tags)) {
            $registered = array_filter($tags, function($tag){
                return strpos('@', $tag);
            });
            array_push($names, $registered);
        } elseif(is_string($tags)) {
            if(get_class($tags) instanceof Controller) {
                $registered = array_map(function ($method) use ($tags) {
                    return "$tags@$method";
                }, get_class_methods($tags));

                array_push($names, $registered);
            } else {
                list($controller, $method) = explode('@', $tags);
                if(get_class($controller) instanceof Controller && in_array($method, get_class_methods($controller))) {
                    $names[] = $tags;
                }
            }

        }

        array_walk($names, function($name) use (&$view, &$identity, &$data, &$message) {
            // map the name to the view
            if($identity instanceof User) {
                $data[NamedView::ID_KEY_USER] = $identity;
            } elseif ($identity instanceof Model && !($identity instanceof User)) {
                $data[NamedView::ID_KEY_RESOURCE] = $identity;
            } else {
                // set the preferences for the rendering engine based on identity preferences
                if(in_array($identity, [NamedView::ID_OPT_DEFAULT, NamedView::ID_OPT_ADMIN, NamedView::ID_OPT_OWNER])) {
                    $data[NamedView::ID_PREF_OPTION] = $identity;
                }

                if($identity === NamedView::ID_OPT_ADMIN) {
                    $view = "$identity.$view";
                }
            }
            // if the view path exist, then set the map
            $path = str_ireplace('.', DIRECTORY_SEPARATOR, $view);
            if(file_exists($path)) {
                $this->views["$name"] = [
                    NamedView::VS_KEY_VIEW => $view,
                    NamedView::VS_KEY_DATA => $data,
                    NamedView::VS_KEY_MESSAGE => $message
                ];
            } else {
                throw new Exception("Named View Exception:\n$name view does not exist");
            }
        });

        return $this;
    }

    /**
     * The NamedView render function returns a view object for a given controller name. The view is rendered with set data 
     * and flash message provided. If authorization is enabled by ACLPolicy, the customized view(s) based on access role is rendered
     * based on the current access level of the user making request. 
     * For example, an admin view page for creating a resource may be different from a default user. With the named view, if the currently logged in user
     * is of the admin role, the admin view is rendered with needed data, else the default view for default user is rendered. 
     * Having multiple views reduces complex logic that arises from branching if-else statements those keeping view files concise.
     * 
     * @param type $tag String, function name mapped to a view file. The $name is provided in the format: method|Controller::method|role@Controller::method
     * @param type $identity mixed, defined access control object or identifier for user|resource. Example user role (admin, default, groupName) or
     * the given instance of the user object
     * @param array $data mixed|array, data passed to the view from the controller
     * @param array $flash mixed|array, flash data sent to the session for the single request
     * @return view instance of view referencing requested web page or resource
     */
    public function render($tag, $identity = null, $data = [], $flash = [])
    {
        $name = null;
        if($this->isRegistered($tag)) {
            $name = $this->getView($tag);
        }

        if(!empty($flash)) {
            // assign key value to session
            array_walk($flash, function($message, $key) {
                session()->flash($key, $message);
                session()->reflash();
            });
        }
        // full validation of the $data array cannot be performed at the moment as $data can take variety of patterns
        if($identity) {
            // set the admin identity flag if the user is an admin
            $data[NamedView::ID_KEY_ADMIN] = $identity === NamedView::ID_KEY_ADMIN ?: null;
            // specify the user instance from which the user preferences can be retrieved. If the ID_KEY_ADMIN is not set, fallback to user
            // preferences - assume, the user preference over admin view where ID_KEY_ADMIN not set
            $data[NamedView::ID_KEY_USER] = ($identity instanceof User) ? $identity : null;
            // set identity to resource for all cases where identity is not admin or user
            $data[NamedView::ID_KEY_RESOURCE] = !($identity instanceof User) ? $identity : null;
            return view($name, $data);
        }
        return view($name);
    }

    /**
     * Render a view for the given user specified by $user parameter
     * @param $function Named function or tag for selecting the view to be rendered
     * @param $user
     * @param array $data
     * @param array $message
     * @return view
     */
    public function userView($function, $user, $data = [], $message = [])
    {
        // renders the user view with preferences of the given user
        $data[NamedView::ID_KEY_ADMIN] = false;
        return $this->render($function, $user, $data, $message);
    }

    public function adminView($function, $user, $data = [], $message = [])
    {
        // renders the admin view with preferences of the given user. The user must be admin
        $data[NamedView::ID_KEY_ADMIN] = true;
        return $this->render($function, $user, $data, $message);
    }

    public function resourceView($function, $resource = null, $user = null, $data = [], $message = [])
    {
        // renders the resource view with preferences of the given user
        $data[NamedView::ID_KEY_ADMIN] = false;
        $data[NamedView::ID_KEY_USER] = false;
        $data[NamedView::ID_KEY_RESOURCE] = true;
        return $this->render($function, $resource, $data, $message);
    }

    /**
     * Assign multiple functions to given named view file.
     * @param type $view
     * @param type $names
     */
    public function register($names = [], $view = '', $identity = null, $data = [], $messages = [], Callable $callback = null)
    {
        // assign multiple function name to view file
        return call_user_func($callback, $names, $view, $identity, $data, $messages);
    }

    public function load()
    {
        // loads the registered views in NamedView/views.php
        return (bool)include_once app_path(__NAMESPACE__ .'/views.php') ?: false;
    }

    public function hasView($view)
    {
        // verify if the named view is in the NamedView list
        $result = false;
        array_walk($this->views, function($values) use ($view, $result){
            $result = in_array($view, $values);
            return $result;
        });
        return $result;
    }

    public function isRegistered($tag)
    {
        if(in_array($tag, array_keys($this->views))) {
            if(!empty($this->views[$tag])) {
                return true;
            }
        }
        return false;
    }

    public function getView($tag)
    {
        // retrieve a given view for the supplied tag
        if(!$this->isRegistered($tag)){
            return;
        }
        return $this->views[$tag][NamedView::VS_KEY_VIEW];
    }

    public function views()
    {
        // return all the views registered as tag=>view pair
        $result = [];
        array_walk($this->views, function($values, $idx) use ($result) {
            if(!empty($values)) {
                $result[$idx] = $values[NamedView::VS_KEY_VIEW];
            }
        });
        return $result;
    }
}
