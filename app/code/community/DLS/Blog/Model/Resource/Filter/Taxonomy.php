<?php

class DLS_Blog_Model_Resource_Filter_Taxonomy extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() {
        $this->_init('dls_blog/filter_taxonomy', 'rel_id');
    }

    public function saveFilterRelation($filter, $taxonomyIds) {
        if (is_null($taxonomyIds)) {
            return $this;
        }
        $oldTaxonomies = $filter->getSelectedTaxonomies();
        $oldTaxonomyIds = array();
        foreach ($oldTaxonomies as $taxonomy) {
            $oldTaxonomyIds[] = $taxonomy->getId();
        }
        $insert = array_diff($taxonomyIds, $oldTaxonomyIds);
        $delete = array_diff($oldTaxonomyIds, $taxonomyIds);
        $write = $this->_getWriteAdapter();
        if (!empty($insert)) {
            $data = array();
            foreach ($insert as $taxonomyId) {
                if (empty($taxonomyId)) {
                    continue;
                }
                $data[] = array(
                    'taxonomy_id' => (int) $taxonomyId,
                    'filter_id' => (int) $filter->getId(),
                    'position' => 1
                );
            }
            if ($data) {
                $write->insertMultiple($this->getMainTable(), $data);
            }
        }
        if (!empty($delete)) {
            foreach ($delete as $taxonomyId) {
                $where = array(
                    'filter_id = ?' => (int) $filter->getId(),
                    'taxonomy_id = ?' => (int) $taxonomyId,
                );
                $write->delete($this->getMainTable(), $where);
            }
        }
        return $this;
    }

}
