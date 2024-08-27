<h3 align="center">MyBB Task Files</h3>

---

<p align="center"> This repository hosts a variety of task files to implement various features to a MyBB forum site.
    <br> 
</p>

## 📜 Table of Contents <a name = "table_of_contents"></a>

- [About](#about)
- [Getting Started](#getting_started)
    - [Dependencies](#dependencies)
    - [File Structure](#file_structure)
    - [Install](#install)
    - [Update](#update)
- [Settings](#settings)
    - [Fill Profile Field With Post Count](#settings_fill_profile_field_with_post_count)
- [Usage](#usage)
- [Built Using](#built_using)
- [Authors](#authors)
- [Acknowledgments](#acknowledgement)
- [Support & Feedback](#support)

## 🚀 About <a name = "about"></a>

This repository hosts a variety of task files to implement various features to a MyBB forum site.

[Go up to Table of Contents](#table_of_contents)

## 📍 Getting Started <a name = "getting_started"></a>

The following information will assist you into getting a copy of each task up and running on your forum.

### Dependencies <a name = "dependencies"></a>

A setup that meets the following requirements is necessary or recommended to use this plugin.

- [MyBB](https://mybb.com/) >= 1.8
- PHP >= 7

### File structure <a name = "file_structure"></a>

  ```
   .
   ├── inc
   │ ├── tasks
   │ │ ├── fillProfileFieldWithPostCount.php
   ```

### Installing <a name = "install"></a>

Follow the next steps in order to install a copy of this plugin on your forum.

1. Download the latest files for each task file.
2. Upload each task file to the MyBB task directory `/inc/tasks/`.
3. Browse to _Tools & Maintenance » Task Manager_ and configure a new task by clicking _Add New Task_.

### Updating <a name = "update"></a>

Follow the next steps in order to update your copy of this plugin.

1. Follow step 1 to 3 from the [Install](#install) section.

[Go up to Table of Contents](#table_of_contents)

## 🛠 Settings <a name = "settings"></a>

Below you can find a description of the plugin settings.

### Fill Profile Field With Post Count <a name = "settings_fill_profile_field_with_post_count"></a>

```PHP
define('ougc\Tasks\FillProfileFieldWithPostCount\SETTINGS', [
    'profileFields' => [
        // where 0 is the custom profile field identified (fid)
        0 => [
            'forumIDs' => [1, 2],// forum identifiers (fid)
            'visible' => [1], // 1 for visible posts
            'queryLimit' => 50
        ],
        0 => [
            'forumIDs' => -1, // all
            'visible' => [-1, 0, 1], // 0 for unapproved, -1 for soft deleted
            'queryLimit' => 50
        ]
    ]
]);
```

[Go up to Table of Contents](#table_of_contents)

## 📖 Usage <a name="usage"></a>

This plugin has no additional configurations; after activating make sure to modify the global settings in order to get
this plugin working.

[Go up to Table of Contents](#table_of_contents)

## ⛏ Built Using <a name = "built_using"></a>

- [MyBB](https://mybb.com/) - Web Framework
- [MyBB PluginLibrary](https://github.com/frostschutz/MyBB-PluginLibrary) - A collection of useful functions for MyBB
- [PHP](https://www.php.net/) - Server Environment

[Go up to Table of Contents](#table_of_contents)

## ✍️ Authors <a name = "authors"></a>

- [@Omar G](https://github.com/Sama34) - Idea & Initial work

[Go up to Table of Contents](#table_of_contents)

## 🎉 Acknowledgements <a name = "acknowledgement"></a>

- [The Documentation Compendium](https://github.com/kylelobo/The-Documentation-Compendium)

[Go up to Table of Contents](#table_of_contents)

## 🎈 Support & Feedback <a name="support"></a>

This is free development and any contribution is welcome. Get support or leave feedback at the
official [MyBB Community](https://community.mybb.com/thread-159249.html).

Thanks for downloading and using our plugins!

[Go up to Table of Contents](#table_of_contents)